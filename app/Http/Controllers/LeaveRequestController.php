<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Models\Mail;
use App\Models\YearlyLeave;
use App\Http\Requests\LeaveRequestRequest;
use App\Http\Requests\SubordinateLeaveRequestRequest;
use App\Http\Controllers\SendMailController;
use App\Helpers\NepaliCalendarHelper;
use App\Helpers\MailHelper;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index()
    {
        $leaveRequests = LeaveRequest::select('id', 'start_date', 'year', 'employee_id', 'end_date', 'days','leave_type_id', 'full_leave', 'half_leave', 'reason', 'acceptance', 'accepted_by')
        ->with(['employee:id,first_name,last_name','leaveType:id,name'])
        ->where('employee_id',\Auth::user()->employee_id)
        ->orderBy('created_at')
        ->orderBy('updated_at')
        ->get();
        $table_title = 'Employee Leave Details';
        
        return view('admin.leaveRequest.index')->with(compact('leaveRequests','table_title'));
    }

    public function leaveDetail(Request $request)
    {
        $leaveRequests = LeaveRequest::select('id', 'start_date', 'year', 'employee_id', 'end_date', 'days','leave_type_id', 'full_leave', 'half_leave', 'reason', 'acceptance', 'accepted_by')
        ->with(['employee:id,first_name,last_name,manager_id','leaveType:id,name'])
        ->with('accepted_by_detail:id,first_name,last_name')
        ->where('acceptance','accepted')
        // ->orWhere('acceptance','rejected')
        ->orderBy('created_at')
        ->orderBy('updated_at');
        // dd($leaveRequests->get());

        if($request->d){
            $leaveRequests = $leaveRequests->where('start_date',$request->d)->get();
        }else{
            $leaveRequests = $leaveRequests->orderBy('start_date')->get();
        }

        $employeeSearch = Employee::select('id','first_name','middle_name','last_name')->where('contract_status','active')->get();
        $table_title = 'Employee Leave Details Lists';
        
        return view('admin.leaveRequest.leave_details')->with(compact('leaveRequests','table_title','employeeSearch'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $leaveTypes = LeaveType::select('id','name')->get();
        return view('admin.leaveRequest.create')->with(compact('leaveTypes'));
    }

    public function createSubOrdinateLeave(){
        $leaveTypes = LeaveType::select('id','name')->get();
        return view('admin.leaveRequest.createSubOrdinateLeave')->with(compact('leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    private function getNepaliYear($year){
        try{
            $date = new NepaliCalendarHelper($year,1);
            $nepaliDate = $date->in_bs();
            $nepaliDateArray = explode('-',$nepaliDate);
            return $nepaliDateArray[0];
        }catch(Exception $e)
        {
            print_r($e->getMessage());
        }
    }
    public function store(LeaveRequestRequest $request)
    {
        $data = $request->validated();
        // dd($data);
        $leave_type_id = $data['leave_type_id'];
        $requested_leave_days = $data['days'];
        $allowed_leave = YearlyLeave::select('days')->where('leave_type_id',$leave_type_id)->where('unit_id',\Auth::user()->employee->unit_id)->get()->first();
        if($allowed_leave)
            $allowed_leave = $allowed_leave->days;
        else
            $allowed_leave = 0;

        $data['employee_id'] = \Auth::user()->employee_id;
        $data['requested_by'] = \Auth::user()->employee_id;

        //nepali date
        $start_year = $this->getNepaliYear($data['start_date']);
        $end_year = $this->getNepaliYear($data['end_date']);
        // dd($start_year,$end_year);

        
        // dd($data['year']);
        if($start_year != $end_year){
            $res = [
                'title' => 'Leave Request Error',
                'message' => 'Leave should be requested from same Nepali year.',
                'icon' => 'error'
            ];
            return redirect()->back()->with(compact('res'));
        }else{
            $data['year'] = $start_year;
        }

        if($data['leave_time'] == 'full')
        {
            $data['full_leave'] = '1';
        }else{
            $data['full_leave'] = '0';
            if($data['leave_time'] == 'first'){
                $data['half_leave'] = 'first';
            }else{
                $data['half_leave'] = 'second';
            }
        }
       $leaveRequest = LeaveRequest::create($data);
        // dd($data,);
        //Send Mail to manager,hr and employee after successful leave request 
        $send_mail = Mail::select('send_mail')->where('name','Leave Request')->first()->send_mail;
        $subject = "Leave Request";
        if($send_mail){
            MailHelper::sendEmail($type=1,$leaveRequest,$subject);
        }

        $res = [
            'title' => 'Leave Request Created',
            'message' => 'Leave Request has been successfully Created',
            'icon' => 'success'
        ];
        return redirect('/leave-request')->with(compact('res'));
    }

    public function storeSubOrdinateLeave(SubordinateLeaveRequestRequest $request)
    {
        $data = $request->validated();
        $data['employee_id'] = $data['employee_id'];
        $data['requested_by'] = \Auth::user()->employee_id;
        
        if($data['leave_time'] == 'full')
        {
            $data['full_leave'] = '1';
        }else{
            $data['full_leave'] = '0';
            if($data['leave_time'] == 'first'){
                $data['half_leave'] = 'first';
            }else{
                $data['half_leave'] = 'second';
            }
        }
        // dd($data);
        $start_year = $this->getNepaliYear($data['start_date']);
        $end_year = $this->getNepaliYear($data['end_date']);
        
         if($start_year != $end_year){
            $res = [
                'title' => 'Leave Request Error',
                'message' => 'Leave should be requested from same Nepali year.',
                'icon' => 'error'
            ];
            return redirect()->back()->with(compact('res'));
        }else{
            $data['year'] = $start_year;
        }


        $leaveRequest = LeaveRequest::create($data);
        //send mail
        $subject = "Subordinate Leave Request";
        $send_mail = Mail::select('send_mail')->where('name','Subordinate Leave')->first()->send_mail;
        if($send_mail)
            MailHelper::sendEmail($type=1,$leaveRequest,$subject);
        $res = [
            'title' => 'Subordinate Leave Request Created',
            'message' => 'Subordinate Leave Request has been successfully Created',
            'icon' => 'success'
        ];
        return redirect('/leave-request/approve')->with(compact('res'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveTypes = LeaveType::select('id','name')->get();
        return view('admin.leaveRequest.edit')->with(compact('leaveRequest','leaveTypes'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LeaveRequestRequest $request, $id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $input = $request->validated();
        
        $leaveRequest->update($input);
        $res = [
            'title' => 'Leave Request Updated',
            'message' => 'Leave Request has been successfully Updated',
            'icon' => 'success'
        ];
        return redirect('/leave-request/details')->with(compact('res'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $leaveRequest = LeaveRequest::where('acceptance','pending')->findOrFail($id);
        $leaveRequest->delete();
        $role = \Auth::user()->role->authority;
        if($role == 'hr' || $role == 'manager')
            return back();
        else
            return redirect('/leave-request');
    }

    public function accept($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id)
        ->update([
            'acceptance' => 'accepted',
            'accepted_by' => \Auth::user()->employee_id
        ]);

        return redirect('/leave-request/approve');
    }

    public function reject($id)
    {
        LeaveRequest::findOrFail($id)
        ->update([
            'acceptance' => 'rejected',
            'accepted_by' => \Auth::user()->employee_id
        ]);

        return redirect('/leave-request/approve');
    }

    private function calculateRemainingTime($allowed_leave,$leave_type_id,$requested_leave_days,$user_id){
        $year = date('Y');
        $already_taken_leaves = LeaveRequest::select('id','days','leave_type_id','year','acceptance','full_leave')
                                        ->where('acceptance','accepted')
                                        ->where('year',$year)
                                        ->where('employee_id', $user_id)
                                        ->where('leave_type_id',$leave_type_id)
                                        ->sum('days');

        $remaining_leave = $allowed_leave - $already_taken_leaves;

        return $remaining_leave ;
    }

    public function approve(Request $request)
    {
        if(\Auth::user()->role->authority == 'employee')
            return abort(401);

        $leaveRequests = LeaveRequest::select('id', 'start_date', 'year', 'employee_id', 'end_date', 'days','leave_type_id', 'full_leave', 'half_leave', 'reason', 'acceptance', 'accepted_by')
        ->with(['employee:id,first_name,last_name,manager_id','leaveType:id,name'])
        ->where('acceptance','pending');

        // dd("Here");

        if(\Auth::user()->role->authority == 'manager'){
            $leaveRequests = $leaveRequests->whereHas('employee',function($query){
                $query->where('manager_id',\Auth::user()->employee_id);
            });
        }


      
        if($request->d)
            $leaveRequests = $leaveRequests->where('start_date',$request->d)->orderBy('created_at')->orderBy('updated_at')->get();
        else
            $leaveRequests = $leaveRequests->orderBy('start_date')->orderBy('created_at')->orderBy('updated_at')->get();
        // dd($leaveRequests[0]->employee->manager);
        return view('admin.leaveRequest.approve_leave')->with(compact('leaveRequests'));
    }
}

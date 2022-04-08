<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\YearlyLeave;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\CarryOverLeave;
use App\Models\NoPunchInNoLeave;
use App\Http\Controllers\DashboardController;
use App\Helpers\NepaliCalendarHelper;

class LeaveReportController extends Controller
{
    protected $employee_join_month;
    protected $employee_join_year;
    protected $thisYear;

    private function getNepaliYear($year){
        try{
            $date = new NepaliCalendarHelper($year,1);
            $nepaliDate = $date->in_bs();
            $nepaliDateArray = explode('-',$nepaliDate);
            $year_month = [$nepaliDateArray[0],$nepaliDateArray[1]];
            return $year_month;
        }catch(Exception $e)
        {
            print_r($e->getMessage());
        }
    }

    public function leaveBalance(Request $request)
    {
        // return  view('admin.leaveBalance.index');
        // dd($request->e);
        
        //this year
        $thisYearMonth = $this->getNepaliYear(date('Y-m-d'));
        $this->thisYear = $thisYearMonth[0];
        $thisYear = $this->thisYear;
        
        if(isset($request->e))
            $employees = Employee::where('contract_status','active')->where('id',$request->e)->paginate(3);
        elseif(isset($request->d))
            $employees = Employee::where('contract_status','active')->paginate(10);
        else
            $employees = Employee::where('contract_status','active')->paginate(3);

        $leaveTypes = LeaveType::select('name','id','gender')->where('status','active')->get();
        $leaveTypesCount = $leaveTypes->count();
        $records = [];
        $dashboardController = new DashboardController;

        foreach($employees as $employee)
        {
            $temp = array();
            $temp['employee_id'] = $employee->id;
            $temp['name'] = $employee->first_name." ".$employee->last_name;
            $temp['leaves'] = array();
           
            $join_year_month = $this->getNepaliYear($employee->join_date);
            $this->employee_join_year = $join_year_month[0];
            $this->employee_join_month = $join_year_month[1];
            
            if(isset($request->d)){
                $start_year = $request->d;
                $end_year = $request->d;
            }else{
                $start_year = $join_year_month[0];
                $end_year = $this->thisYear;
            }
            for($year=$start_year; $year <= $end_year; $year++)
            {
                $temp['leaves']['year'] = $year;
                foreach($leaveTypes as $type)
                {
                    if(strtolower($type->gender) == 'male' && strtolower($employee->gender) == 'male'){  
                        $leaveTypeBalance = $this->getEmployeeLeaveBalance($employee,$type,$year);
                        $temp['leaves'][$type->name]= [
                            'allowed' => $leaveTypeBalance['allowed'],
                            'accrued' => $leaveTypeBalance['accrued'],
                            'taken' => $leaveTypeBalance['taken'],
                            'balance' => $leaveTypeBalance['balance']
                        ];
                       
                    }elseif(strtolower($type->gender) == 'female' && strtolower($employee->gender) == 'female'){
                        $leaveTypeBalance = $this->getEmployeeLeaveBalance($employee,$type,$year);                        
                        $temp['leaves'][$type->name]= [
                            'allowed' => $leaveTypeBalance['allowed'],
                            'accrued' => $leaveTypeBalance['accrued'],
                            'taken' => $leaveTypeBalance['taken'],
                            'balance' => $leaveTypeBalance['balance']
                        ];
                    }elseif(strtolower($type->gender) == 'all'){
                        $leaveTypeBalance = $this->getEmployeeLeaveBalance($employee,$type,$year);
                        $temp['leaves'][$type->name]= [
                            'allowed' => $leaveTypeBalance['allowed'],
                            'accrued' => $leaveTypeBalance['accrued'],
                            'taken' => $leaveTypeBalance['taken'],
                            'balance' => $leaveTypeBalance['balance']
                        ];
                    }
                }
                $total_unpaid_leaves = LeaveRequest::select('id','days','leave_type_id','year','acceptance','employee_id')
                                        ->where('acceptance','accepted')
                                        ->where('employee_id', $employee->id)
                                        ->where('year',$year)
                                        ->whereDoesntHave('leaveType',function($query){
                                            $query->where('paid_unpaid','1');
                                        })
                                        ->sum('days');
                
                $temp['total_unpaid_leaves'] = $total_unpaid_leaves;
                array_push($records,$temp);
            }
            
        }

        return  view('admin.leaveBalance.index',compact('records','leaveTypes','leaveTypesCount','thisYear','employees'));
    }

    private function getEmployeeLeaveBalance($employee,$type,$year){
        $dashboardController = new DashboardController;
        
        //gives carryover
        $allowedLeave = $dashboardController->getAllowedLeaveDays($employee->unit_id,$type->id,$year,$employee->id);
        
        if($this->employee_join_year == $year){
            $remaining_month = 13-$this->employee_join_month;
            $allowedLeave = round(($allowedLeave/12*$remaining_month)*2)/2;
        }

        //for carryover = 0
        // $allowedLeave = $this->getAllowedLeaveDays($employee->unit_id,$type->id,$year);
        $acquiredLeave = $allowedLeave;
        
        $fullLeaveTaken = LeaveRequest::select('id','days','leave_type_id','full_leave')
                                    ->where('acceptance','accepted')
                                    ->where('year',$year)
                                    ->where('employee_id',$employee->id)
                                    ->where('leave_type_id',$type->id)
                                    ->where('full_leave',"1")
                                    ->sum('days');

        $halfLeaveTaken = LeaveRequest::select('id','days','leave_type_id','full_leave')
                                    ->where('acceptance','accepted')
                                    ->where('year',$year)
                                    ->where('employee_id', $employee->id)
                                    ->where('leave_type_id',$type->id)
                                    ->where('full_leave',"0")
                                    ->sum('days');

        $leaveTaken = $fullLeaveTaken + 0.5 * $halfLeaveTaken;
        $balance = $acquiredLeave - $leaveTaken;

        $lists=[
            'allowed' => $allowedLeave,
            'accrued' => round($acquiredLeave,2),
            'taken' => $leaveTaken,
            'balance' => round($balance,2)
        ];
        return $lists;
    }

    private function getAllowedLeaveDays($unit_id,$leaveType,$year)
    {
        // dd($org_id,$leaveType,$year);
        $allowedLeave = YearlyLeave::select('days')
                                ->where('year',$year)
                                ->where('unit_id',$unit_id)
                                ->where('leave_type_id',$leaveType)
                                ->where('status','active')
                                ->get()->first();
        
        // dd($allowedLeave->exists());
        if(isset($allowedLeave) && ($allowedLeave->exists() == 1))
            $allowedLeave = $allowedLeave->days;
        else
            $allowedLeave = 0;

        return $allowedLeave;
    }

    public function employeesOnLeave(Request $request){
        if(isset($request->d))
            $date = $request->d;
        else
            $date = date('Y-m-d');

        $acceptedRequests = LeaveRequest::select('id','days','leave_type_id','full_leave','start_date','end_date','employee_id','half_leave','reason','acceptance')
                                    ->where('acceptance','accepted')
                                    ->with('employee:id,first_name,last_name,middle_name')
                                    ->with('leaveType:id,name')
                                    ->whereDate('end_date', '>=', $date)
                                    ->whereDate('start_date','<=',$date)        
                                    ->get();
        return view('admin.report.employeesOnLeave')->with(compact('acceptedRequests'));;
    }

    public function noPunchInNoLeave(Request $request)
    {
        $code = 'OXqSTexF5zn4uXSp';

        $records = NoPunchInNoLeave::select('id','employee_id','date')->with('employee:id,first_name,middle_name,last_name,manager_id');
        if(isset($request->e))
            $records =  $records->where('employee_id',$request->e);
        elseif(isset($request->d))
            $records =  $records->whereDate('date',$request->d);
        else
            $records = $records->whereDate('date',date('Y-m-d'));
                
        $records = $records->orderBy('date','desc')->get();

        $employeeSearch = Employee::select('id','first_name','middle_name','last_name')->where('id',$request->e)->get();
        // ->first();
        
        return view('admin.report.noPunchInNoLeave')->with(compact('records','code','employeeSearch'));
    }
}

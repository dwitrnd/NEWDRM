<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\YearlyLeave;
use App\Models\Employee;
use App\Models\Attendance;

class DashboardController extends Controller
{
    public function index(Request $request)
    {       
        $state = 1;
        if($this->recordRowExists())
        {
            $state = 2;
            if($this->hasPunchOut())
            {
                $state = 3;
            }
        }

        //set punch-in state;
        \Session::put('punchIn', $state);
        \Session::put('userIp', request()->ip());

        $leaveBalance = $this->getLeaveBalance();
        $birthdayList = $this->getBirthdayList();
        $leaveList = $this->getLeaveList();
        
        return view('admin.dashboard.index')->with(compact('leaveBalance','birthdayList','leaveList'));
    }

    private function getLeaveList()
    {
        $leaveList = LeaveRequest::select('id','employee_id','start_date','end_date','days','full_leave','half_leave','leave_type_id')
                        ->with('employee:id,first_name,last_name,middle_name')
                        ->with('leaveType:id,name')
                        ->where('acceptance','accepted')
                        ->whereDate('start_date','<=',date('Y-m-d'))
                        ->whereDate('end_date','>=',date('Y-m-d'))
                        ->get();

        return $leaveList;
    }

    private function getBirthdayList()
    {
        $curr_month = date('m');
        $curr_day = date('d');

        $next_month = date('m', strtotime("+30 days"));
        $next_day = date('d', strtotime("+30 days"));

        $birthdayList = Employee::select('first_name','last_name','middle_name','date_of_birth')
                                ->where('contract_status','active')
                                ->whereMonth('date_of_birth','>',$curr_month)
                                ->orWhere(function($query) use($curr_month,$curr_day){
                                    $query->whereMonth('date_of_birth',$curr_month)
                                        ->whereDay('date_of_birth','>',$curr_day);
                                })
                                ->whereMonth('date_of_birth','<=',$next_month)
                                ->orWhere(function($query) use($next_month,$next_day){
                                    $query->whereMonth('date_of_birth',$next_month)
                                        ->whereDay('date_of_birth','<=',$next_day);
                                })
                                ->orderByRaw("DATE_FORMAT(date_of_birth,'%d-%M-%Y')")
                                ->get();

        return $birthdayList;
    }

    private function getLeaveBalance()
    {
        // dd("Here");
        $year = date('Y');
        $month = date('m');
        $org_id = \Auth::user()->employee->organization_id;
        $leaveTypes = LeaveType::select('name','id')->get();

        $lists = array();
        foreach($leaveTypes as $leaveType)
        {
            $allowedLeave = $this->getAllowedLeaveDays($org_id,$leaveType->id,$year);
            $acquiredLeave = $allowedLeave / 12 * $month;
            
            $fullLeaveTaken = LeaveRequest::select('id','days','leave_type_id','full_leave')
                                        ->where('acceptance','accepted')
                                        ->where('employee_id', \Auth::user()->employee_id)
                                        ->where('leave_type_id',$leaveType->id)
                                        ->where('full_leave',"1")
                                        ->sum('days');

            $halfLeaveTaken = LeaveRequest::select('id','days','leave_type_id','full_leave')
                                        ->where('acceptance','accepted')
                                        ->where('employee_id', \Auth::user()->employee_id)
                                        ->where('leave_type_id',$leaveType->id)
                                        ->where('full_leave',"0")
                                        ->sum('days');

            $leaveTaken = $fullLeaveTaken + 0.5 * $halfLeaveTaken;
            $balance = $acquiredLeave - $leaveTaken;

            $lists[$leaveType->name] = [
                'allowed' => $allowedLeave,
                'accrued' => round($acquiredLeave,2),
                'taken' => $leaveTaken,
                'balance' => round($balance,2)
            ];
        }

        return $lists;
    }

    private function getAllowedLeaveDays($unit_id,$leaveType,$year)
    {
        $allowedLeave = YearlyLeave::select('days')
                                ->where('year',$year)
                                ->where('unit_id',$unit_id)
                                ->where('leave_type_id',$leaveType)
                                ->where('status','active')
                                ->get()->first();
        
        if(isset($allowedLeave) && ($allowedLeave->exists() == 1))
            $allowedLeave = $allowedLeave->days;
        else
            $allowedLeave = 0;

        return $allowedLeave;
    }

    private function recordRowExists()
    {
        $employee_id = \Auth::user()->employee_id;
        $today = date('Y-m-d');
        $rowExists = Attendance::where('employee_id',$employee_id)
        ->whereDate('created_at',$today)
        ->count();

        if($rowExists == 0)
            return false;
        else
            return true;
    }

    private function hasPunchOut()
    {
        $employee_id = \Auth::user()->employee_id;
        $today = date('Y-m-d');
        $punchOutTime = Attendance::select('punch_out_time')
        ->where('employee_id',$employee_id)
        ->whereDate('created_at',$today)
        ->first();

        if($punchOutTime->punch_out_time == null)
        {
            return false;
        }else{
            return true;
        }
    }
}

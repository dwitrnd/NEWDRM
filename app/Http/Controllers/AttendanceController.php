<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Exports\TerminatedAttendanceExport;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Attendance;
use App\Models\MailControl;
use App\Models\LeaveRequest;
use App\Models\NoPunchInNoLeave;
use App\Http\Controllers\SendMailController;
use App\Helpers\MailHelper;
use App\Helpers\NepaliCalendarHelper;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Time;
use Carbon\Carbon;
use App\Mail\LatePunchInMail;
use App\Mail\EarlyPunchOutMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


class AttendanceController extends Controller
{
    private $redirect_to = '/dashboard';
    private $verificationCode = 'OXqSTexF5zn4uXSp';

    private function recordRowExists($employee_id)
    {
        // $employee_id = \Auth::user()->employee_id;
        $today = date('Y-m-d');
        $rowExists = Attendance::where('employee_id', $employee_id)
            ->whereDate('punch_in_time', $today)
            ->count();

        if ($rowExists == 0)
            return false;
        else
            return true;
    }

    private function hasPunchOut($employee_id)
    {
        $today = date('Y-m-d');
        $punchOutTime = Attendance::select('punch_out_time')
            ->where('employee_id', $employee_id)
            ->whereDate('punch_in_time', $today)
            ->first();

        if ($punchOutTime->punch_out_time == null) {
            return false;
        } else {
            return true;
        }
    }

    public function index()
    {
        //state = 1 ; no punch-in
        //state = 2; no punch-out
        //state = 3; punch-out
        // dd(request()->ip());

        $state = 1;
        if ($this->recordRowExists(\Auth::user()->employee_id)) {
            $state = 2;
            if ($this->hasPunchOut()) {
                $state = 3;
            }
        }

        //set punch-in state;
        \Session::put('punchIn', $state);
        // dd($state);
        if ($state == 1) {
            return view('admin.attendance.punchIn')->with(['code' => $this->verificationCode]);
        } else {
            return redirect($this->redirect_to);
        }
    }

    public function punchIn(Request $request)
    {
        // $state=1 hr punch in
        // $state=2  individual punch in
        // $state=3 force punch in

        $successful_punch_in_res = [
            'title' => 'Employee Punched In',
            'message' => 'Employee has been successfully Punched In',
            'icon' => 'success'
        ];

        $failure_punch_in_res = [
            'title' => 'Employee Punch In Failed',
            'message' => 'Employee cannot be Punched In',
            'icon' => 'warning'
        ];
        //Employee punch in by HR
        if ($request->id) {
            $employee_id = $request->id;
            $no_punch_in_no_leave_record = NoPunchInNoLeave::select('id')->where('employee_id', $employee_id)->get();
            if (!$no_punch_in_no_leave_record->isEmpty()) {
                $res = [
                    'title' => 'Employee Punch In Failed',
                    'message' => 'Employee has No Punch-In and No Leave Record. Please clear the record first.',
                    'icon' => 'warning'
                ];
                return redirect('/employee')->with(compact('res'));
            }

            $state = 1;
            $request->merge(['reason' => 'HR Punch In Due to Multiple late Punch In']);
            $attendance = $this->takeAttendance($employee_id, $request, $state);
            if ($attendance) {
                $res = $successful_punch_in_res;
            } else {
                $res = $failure_punch_in_res;
            }
            return redirect('/employee')->with(compact('res'));
        } else {
            //Individual Punch In
            $state = 2;
            $employee_id = \Auth::user()->employee_id;
            $attendance = $this->takeAttendance($employee_id, $request, $state);
            if ($attendance) {
                $res = $successful_punch_in_res;
            } else {
                $res = $failure_punch_in_res;
            }

            return redirect($this->redirect_to)->with(compact('res'));

        }
    }
    //force punch in for no-punch-in-no-leave request
    public function forcePunchIn(Request $request, $id)
    {
        //Employee force punch in by HR
        $state = 3;
        $employee_id = $request->employee_id;
        $request->merge(['reason' => 'Forced Punch In Due to No Punch In No leave Record']);
        $attendance = $this->takeAttendance($employee_id, $request, $state);
        if ($attendance) {
            $res = [
                'title' => 'Employee Punched In',
                'message' => 'Employee has been successfully Punched In',
                'icon' => 'success'
            ];
            $noPunchInNoLeaveRecord = NoPunchInNoLeave::findOrFail($id);
            $noPunchInNoLeaveRecord->delete();
        } else {
            $res = [
                'title' => 'Employee Punch In Failed',
                'message' => 'Employee cannot be Punched In',
                'icon' => 'warning'
            ];
        }
        return redirect()->back()->with(compact('res'));
    }

    public function takeAttendance($employee_id, $request, $state)
    {
        if (!$this->recordRowExists($employee_id)) {
            if ($request->code == $this->verificationCode) {
                //no punch-in no leave-> force punch in
                if ($state == 3) {
                    $noPunchInNoLeaveRecordDate = NoPunchInNoLeave::select('date')->where('id', $request->id)->first()->date;
                    $presentTime = date('Y-m-d', strtotime($noPunchInNoLeaveRecordDate));
                    $punch_in_time = Carbon::createFromFormat('Y-m-d H:i:s', $noPunchInNoLeaveRecordDate . ' 10:00:00');
                } else {
                    $presentTime = Carbon::now()->format('Y-m-d');
                    $punch_in_time = Carbon::now()->toDateTimeString();
                }

                $hasAnyLeave = LeaveRequest::whereDate('start_date', '<=', $presentTime)
                    ->whereDate('end_date', '>=', $presentTime)
                    ->where('employee_id', $employee_id)
                    ->where('acceptance', 'accepted')
                    ->count();

                //Custom shift Employee
                $employee_shift_time = Employee::select('id', 'shift_id', 'start_time', 'end_time')->where('id', \Auth::user()->employee_id)->first();
                $weekdays = true;

                if (date('D') == 'Sat' || date('D') == 'Sun') { //weekend punch in
                    $maxTime = date('H:i:s', strtotime('+60seconds'));
                    $weekdays = false;
                } else if ($employee_shift_time->shift_id == '2')   //shift_id = 2 ->custom shift
                    $maxTime = $employee_shift_time->start_time;    //maxPunch in time for custom shift employees
                else
                    $maxTime = Time::select('id', 'time')->where('id', '1')->first()->time;    //max punch in time for other shifts
                // dd($maxTime);
                $first_half_leave_max_punch_in_time = Time::select('id', 'time')->where('id', '2')->first()->time;
                if ($hasAnyLeave == 0) {
                    $maxTime = strtotime(date('Y-m-d') . ' ' . $maxTime);
                } else {
                    $leave = LeaveRequest::whereDate('start_date', '<=', $presentTime)
                        ->whereDate('end_date', '>=', $presentTime)
                        ->where('employee_id', $employee_id)
                        ->where('acceptance', 'accepted')
                        ->first();

                    $full_leave = $leave->full_leave;
                    if ($full_leave == 0) {
                        $half = $leave->half_leave;
                        if ($half == 'first') {
                            $maxTime = strtotime(date('Y-m-d') . ' ' . $first_half_leave_max_punch_in_time);
                        }
                    }
                }

                $isLate = strtotime(Carbon::now()) <= $maxTime ? '0' : '1';
                // dd($isLate,$weekdays,strtotime($maxTime),strtotime(Carbon::now()));

                // if reason is null for isLate true throw error
                if ($isLate && $weekdays) {
                    // dd($isLate,$weekdays,"jhere");
                    $request->validate([
                        'reason' => 'required|string|min:25',
                    ]);
                } else if (!$weekdays) {
                    $request->validate([
                        'reason' => 'required|string',
                    ]);
                }
                // dd("late punch in and no no remarks validation in weekend",$isLate, $request->reason);
                if ($state == 3) {
                    Log::info('Employee_id = ' . $employee_id . ', punch_in_time = ' . $punch_in_time . ', late_punch_in = ' . $isLate . ', reason = ' . $request->reason . "state 3");
                    $attendance = Attendance::create([
                        'employee_id' => $employee_id,
                        'punch_in_time' => $punch_in_time,
                        'punch_out_time' => Carbon::createFromFormat('Y-m-d H:i:s', $noPunchInNoLeaveRecordDate . ' 18:00:00'),
                        'punch_in_ip' => request()->ip(),
                        'punch_out_ip' => request()->ip(),
                        'late_punch_in' => $isLate,
                        'reason' => $request->reason
                    ]);
                    \Session::put('punchIn', '1');
                } else {
                    Log::info('Employee_id = ' . $employee_id . ', punch_in_time = ' . $punch_in_time . ', late_punch_in = ' . $isLate . ', reason = ' . $request->reason);
                    $attendance = Attendance::create([
                        'employee_id' => $employee_id,
                        'punch_in_time' => $punch_in_time,
                        'punch_in_ip' => request()->ip(),
                        'late_punch_in' => $isLate,
                        'reason' => $request->reason
                    ]);
                    \Session::put('punchIn', '2');
                }
                //Send Mail to manager,hr and employee after late punch in 
                $subject = "Late Punch In";
                $send_mail = MailControl::select('send_mail')->where('name', 'Late Punch In')->first()->send_mail;
                $ccList = MailHelper::getHrEmail();
                array_push($ccList, MailHelper::getManagerEmail($attendance->employee_id));

                try {
                    if ($attendance->late_punch_in && $send_mail) {
                        Mail::to(\Auth::user()->employee->email)
                            ->cc($ccList)
                            ->send(new LatePunchInMail($attendance));
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', $e->getMessage());
                }

            }
            return true;
        }
        return false;
    }

    public function punchOut(Request $request)
    {
        $employee_id = \Auth::user()->employee_id;
        $employee_full_name = \Auth::user()->employee->first_name . ' ' . \Auth::user()->employee->middle_name . ' ' . \Auth::user()->employee->last_name;
        $today = date('Y-m-d');

        if ($request->id) {
            $employee_id = $request->id;
        }

        if ($this->recordRowExists($employee_id) && !$this->hasPunchOut($employee_id)) {
            // $presentTime = strtotime(Carbon::now());
            $presentTime = Carbon::now()->format('Y-m-d');

            $hasAnyLeave = LeaveRequest::whereDate('start_date', '<=', $presentTime)
                ->whereDate('end_date', '>=', $presentTime)
                ->where('employee_id', \Auth::user()->employee_id)
                ->where('acceptance', 'accepted')
                ->count();

            //Custom shift Employee
            $employee_shift_time = Employee::select('id', 'shift_id', 'start_time', 'end_time')->where('id', \Auth::user()->employee_id)->first();

            if (date('D') == 'Sat' || date('D') == 'Sun') //weekend punch out
                $min_punch_out_time = date('H:i:s');
            else if ($employee_shift_time->shift_id == '2')   //shift_id = 2 ->custom shift
                $min_punch_out_time = $employee_shift_time->end_time;    //maxPunchOut time for custom shift employees
            else
                $min_punch_out_time = Time::select('id', 'time')->where('id', '3')->first()->time;    //max punch out time for other shifts

            $second_half_leave_min_punch_out_time = Time::select('id', 'time')->where('id', '4')->first()->time;
            if ($hasAnyLeave == 0) {
                $minTime = strtotime(date('Y-m-d') . ' ' . $min_punch_out_time);
                // dd($minTime,'noleave');
            } else {
                $leave = LeaveRequest::whereDate('start_date', '<=', $presentTime)
                    ->whereDate('end_date', '>=', $presentTime)
                    ->where('employee_id', \Auth::user()->employee_id)
                    ->where('acceptance', 'accepted')
                    ->first();

                $full_leave = $leave->full_leave;

                if ($full_leave == 0) {
                    $half = $leave->half_leave;
                    if ($half == 'second')
                        $minTime = strtotime(date('Y-m-d') . ' ' . $second_half_leave_min_punch_out_time);
                    else
                        $minTime = strtotime(date('Y-m-d') . ' ' . $min_punch_out_time);
                } else
                    $minTime = strtotime(date('Y-m-d') . ' ' . $min_punch_out_time);
                // dd($minTime,'first');
            }
            // dd(date('Y-m-d H:i',$minTime),'outside');
            $issueForcedLeave = strtotime(Carbon::now()) < $minTime ? '1' : '0';
            $attendance = Attendance::select('punch_out_time')
                ->where('employee_id', $employee_id)
                ->whereDate('punch_in_time', $today)
                ->update([
                    'punch_out_time' => Carbon::now()->toDateTimeString(),
                    'punch_out_ip' => request()->ip(),
                ]);
            \Session::put('punchIn', '3');

            if ($issueForcedLeave == 1) {
                try {
                    $LeaveRequests = LeaveRequest::create([
                        'employee_id' => \Auth::user()->employee_id,
                        'start_date' => date('Y-m-d'),
                        'end_date' => date('Y-m-d'),
                        'days' => '1',
                        'year' => $this->getNepaliYear(date('Y-m-d')),
                        'leave_type_id' => '1',
                        'full_leave' => '0',
                        'half_leave' => 'second',
                        'reason' => 'Forced (System)',
                        'acceptance' => 'accepted',
                        'requested_by' => \Auth::user()->employee_id,
                        'accepted_by' => NULL
                    ]);

                    //Send Mail to manager,hr and employee after early punch out 
                    $send_mail = MailControl::select('send_mail')->where('name', 'Early Punch Out')->first()->send_mail;
                    $ccList = MailHelper::getHrEmail();
                    array_push($ccList, MailHelper::getManagerEmail($employee_id));

                    if ($send_mail) {
                        Mail::to(\Auth::user()->employee->email)
                            ->cc($ccList)
                            ->send(new EarlyPunchOutMail($employee_full_name));
                    }
                } catch (Exception $e) {
                    redirect()->back()->with('error', $e->getMessage());
                }
            }
        }
        return redirect($this->redirect_to);
    }
    
    public function myPunchIn()
    {
        $myPunchInList = Attendance::select('id', 'employee_id', 'punch_in_time', 'punch_in_ip', 'punch_out_time', 'punch_out_ip', 'missed_punch_out', 'late_punch_in', 'reason')
            ->where('employee_id', \Auth::user()->employee_id)
            ->orderBy('punch_in_time', 'desc')
            ->get();

        return view('admin.attendance.myPunchIn')->with(compact('myPunchInList'));
    }
    public function getNepaliYear($year)
    {
        try {
            $date = new NepaliCalendarHelper($year, 1);
            $nepaliDate = $date->in_bs();
            $nepaliDateArray = explode('-', $nepaliDate);
            return $nepaliDateArray[0];
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function ForcePunchOut()
    {
        $attendances = Attendance::whereDate('punch_in_time', date('Y-m-d'))
            ->where('punch_out_time', NULL)
            ->update(['punch_out_time' => date('Y-m-d H:i:s'), 'punch_out_ip' => '110.44.116.42']);

        if ($attendances != 0)
            return response()->json([
                'success' => true,
                'message' => "Punch Out Successfully.",
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => "Punch Out Unsucessful. Everyone must've punched out already.",
            ]);
    }

    public function report(Request $request)
    {
        try {
            $data=$request;
            
            $employees = Employee::select('id', 'first_name', 'middle_name', 'last_name', 'manager_id')
            ->where('contract_status', 'active');
            $attendance=Attendance::first();
                
            $employees = $employees->when(isset($request->e), function ($query) use ($request) {
                return $query->whereHas('attendances', function ($query) use ($request) {
                    return $query->where('employee_id', $request->e);
                });
            })->when(isset($request->sd), function ($query) use ($request) {
                return $query->whereHas('attendances', function ($query) use ($request) {
                    return $query->whereDate('punch_in_time', '>=', Carbon::parse($request->sd));
                });
            })->when(isset($request->ed), function ($query) use ($request) {
                return $query->whereHas('attendances', function ($query) use ($request) {
                    return $query->whereDate('punch_in_time', '<=', Carbon::parse($request->ed));
                });
            })->get();
                $attendance=Attendance::where("employee_id", $employees->first()->id)->get()->sortByDesc('id')->take(5);
                $holidays = Holiday::where('female_only', '0')->pluck('date')->toArray();
                $startDate =isset($request->sd) ? Carbon::parse($request->sd) : Carbon::now()->subDays(30);
                $endDate=isset($request->ed) ? Carbon::parse($request->ed): Carbon::now();
    
                $dates = collect();
                $currentDate = $startDate->copy();
                $iteration=0;
                while ($currentDate->lte($endDate)) {
                    if (!$currentDate->isWeekend() && !in_array($currentDate->format('Y-m-d'), $holidays)) {
                        $dates->push($currentDate->copy());
                        $iteration++;
                    }
                    
                    $currentDate->addDay();
                }
            $employeeList = Employee::select('id', 'first_name', 'middle_name', 'last_name')->where('contract_status', 'active')->get();
    
            return view('admin.report.attendance')->with(compact('employees', 'employeeList', 'dates',"data"));
        } catch (\Exception $e) {
            return redirect()->back()->with("error", "Oops! Something went wrong");
        }
    }
    public function export(Request $request) 
    {
        return Excel::download(new AttendanceExport($request), 'attendancereport.xlsx');
    }
    
    public function terminatedreport(Request $request)
    {
        try {
            $data=$request;
            $employees = Employee::select('id', 'first_name', 'middle_name', 'last_name', 'terminated_date')
            ->where('contract_status', 'terminated');

            $employees = $employees->when(isset($request->e), function ($query) use ($request) {
                return $query->whereHas('attendances', function ($query) use ($request) {
                    return $query->where('employee_id', $request->e);
                });
            })->when(isset($request->sd), function ($query) use ($request) {
                return $query->whereHas('attendances', function ($query) use ($request) {
                    return $query->whereDate('punch_in_time', '>=', Carbon::parse($request->sd));
                });
            })->when(isset($request->ed), function ($query) use ($request) {
                return $query->whereHas('attendances', function ($query) use ($request) {
                    return $query->whereDate('punch_in_time', '<=', Carbon::parse($request->ed));
                });
            })->get();
                $attendance=Attendance::where("employee_id", $employees->first()->id)->get()->sortByDesc('id')->take(5);
                $holidays = Holiday::where('female_only', '0')->pluck('date')->toArray();
                $startDate =isset($request->sd) ? Carbon::parse($request->sd) : Carbon::now()->subDays(30);
                $endDate=isset($request->ed) ? Carbon::parse($request->ed): Carbon::now();
    
                $dates = collect();
                $currentDate = $startDate->copy();
                $iteration=0;
                while ($currentDate->lte($endDate)) {
                    if (!$currentDate->isWeekend() && !in_array($currentDate->format('Y-m-d'), $holidays)) {
                        $dates->push($currentDate->copy());
                        $iteration++;
                    }
                    
                    $currentDate->addDay();
                }
            $employeeList = Employee::select('id', 'first_name', 'middle_name', 'last_name')->where('contract_status', 'terminated')->get();
    
            return view('admin.report.terminatedAttendance')->with(compact('employees', 'employeeList', 'dates','data'));
        } catch (\Exception $e) {
            return redirect()->back()->with("error", "Oops! Something went wrong");
        }
    }
    public function exportTerminated(Request $request) 
    {
        return Excel::download(new TerminatedAttendanceExport($request), 'attendancereport_terminated.xlsx');
    }

}

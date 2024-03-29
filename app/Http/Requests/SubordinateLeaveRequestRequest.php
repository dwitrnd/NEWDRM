<?php

namespace App\Http\Requests;
use App\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;

class SubordinateLeaveRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(\Auth::user()->role->authority == 'hr' || \Auth::user()->role->authority == 'manager')
            return true;
        else
            return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd(\Route::current()->getActionName());
        // dd(\Request::route()->getName());
        if(array_key_exists('employee_id',\Request::input()) == false)
            return ['employee_id' => 'required|exists:employees,id'];
        
        $today = date('Y-m-d');

        if(\Route::current()->getActionName() == "App\Http\Controllers\LeaveRequestController@update")
            $today = date("Y-m-d", strtotime ( '-1 month' , strtotime ( $today ) )) ;

        $start_date = \Request::input('start_date');
        $end_date = \Request::input('end_date');
        $leave_type_id = \Request::input('leave_type_id');
        $calcDay = Helper::getDays($start_date, $end_date,$leave_type_id,\Request::input('employee_id'));
        if(\Request::input('leave_time') != 'full'){
            $calcDay = $calcDay/2;
        }
        // dd($start_date,$end_date,$calcDay);
        return [
            'employee_id' => 'required|exists:employees,id',
            'start_date' => 'required|date|after_or_equal:'.$today,
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|in:'.$calcDay,
            'leave_type_id' => 'required|integer',
            'leave_time' => 'required|in:full,first,second',
            'reason' => 'required|string',
        ];
    }
}

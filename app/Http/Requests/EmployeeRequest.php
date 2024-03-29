<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'employee_id' => 'required',
            'employee_id' => 'required|unique:employees,employee_id,'.$this->id,
            'first_name' => 'required|max:255|string',
            'last_name' => 'required|max:255|string',
            'middle_name' => 'nullable|max:255|string',
            'date_of_birth' => 'required|date',
            'marital_status' => 'required|max:255|string',
            'spouse_name' => 'nullable|max:255|string',
            'gender' => 'required|max:255|string',
            'father_name' => 'nullable|max:255|string',
            'mother_name' => 'nullable|max:255|string',
            'grand_father' => 'nullable|max:255|string',
            'mobile' => 'required|digits:10',
            'alternative_mobile' => 'nullable|digits:10',
            'home_phone' => 'nullable|string|max:10',
            'image' => 'nullable|mimes:jpeg,jpg,png',
            'alter_email' => 'required|email|max:255',
            'cv' => 'nullable|mimes:pdf',
            'country' => 'required|max:255|string',
            'nationality' => 'nullable|max:255|string',
            'profile' => 'nullable|max:255|string',
            'blood_group' => 'nullable|max:5|string',
            'permanent_address' => 'required|max:255|integer',
            'permanent_district' => 'required|max:255|integer',
            'permanent_municipality' => 'required|max:255|string',
            'permanent_ward_no' => 'required|digits_between:1,2',
            'permanent_tole' => 'required|max:255|string',
            'temp_add_same_as_per_add' => 'required|max:255|integer',
            'temporary_address' => 'required_if:temp_add_same_as_per_add,0|nullable|max:255|string',
            'temporary_district' => 'required_if:temp_add_same_as_per_add,0|nullable|max:255|string',
            'temporary_municipality' => 'required_if:temp_add_same_as_per_add,0|nullable|max:255|string',
            'temporary_ward_no' => 'required_if:temp_add_same_as_per_add,0|nullable|max:50|integer',
            'temporary_tole' => 'required_if:temp_add_same_as_per_add,0|nullable|max:255|string',
            'join_date' => 'nullable|date',
            'intern_trainee_ship_date' => 'nullable|date',
            'service_type' => 'required|exists:service_types,id',
            'manager_id' => 'nullable|exists:employees,id',
            'designation_id' => 'required|max:255|integer',
            'designation_change_date' => 'nullable|date',
            'organization_id' => 'required|max:255|integer',
            'unit_id'  => 'required|max:255|integer|exists:units,id',
            'department_id'  => 'required|max:255|integer|exists:departments,id',
            'email' => 'required|email|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$this->id.',employee_id',
            'role' => 'required|exists:roles,id',
            'shift_id' => 'required|max:255|integer',
            'start_time' => 'date_format:H:i|nullable',
            'end_time' => 'date_format:H:i|nullable',
            'remarks' => 'nullable|max:255|string',
            'pan_number' => 'nullable|digits:9|numeric',
            'cit_number' => 'nullable|min:13|max:13|string',
            'ssf_id' => 'nullable|digits:11|numeric',
            'nibl_account_number' => 'nullable|digits:14|numeric',
            'emg_first_name' =>'required|max:255|string',
            'emg_middle_name' =>'nullable|max:255|string',
            'emg_last_name' =>'required|max:255|string',
            'emg_relationship' =>'required|max:255|string',
            'emg_contact' =>'required|digits_between:7,15',
            'emg_alternate_contact' =>'nullable|string|max:15',
        ];
    }
}

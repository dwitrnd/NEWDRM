<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'days' => 'required',
            'leave_type_id' => 'required',
            'full_leave' => 'required',
            'half_leave' => 'nullable',
            'reason' => 'required',
            'acceptance' => 'required',
            'accepted_by' => 'nullable',
        ];
    }
}

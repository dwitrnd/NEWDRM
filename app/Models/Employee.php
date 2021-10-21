<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public $fillable = [
        'version',
        'employee_id',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'marital_status',
        'gender',
        'father_name',
        'mother_name',
        'grand_father',
        'mobile',
        'alternative_mobile',
        'home_phone',
        'image_name',
        'alter_email',
        'cv_file_name',
        'country',
        'nationality',
        'profile',
        'blood_group',
        'permanent_address',
        'permanent_district',
        'permanent_municipality',
        'permanent_ward_no',
        'permanent_tole',
        'temp_add_same_as_per_add',
        'temporary_address',
        'temporary_district',
        'temporary_municipality',
        'temporary_ward_no',
        'temporary_tole',
        'join_date',
        'intern_trainee_ship_date',
        'service_type',
        'manager_id',
        'designation_id',
        'designation_change_date',
        'organization_id',
        'unit_id',
        'email',
        'emp_shift',
        'remarks',
    ];
}

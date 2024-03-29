<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    public $fillable = [
        'version',
        'job_title_name',
        'job_description'
    ];
    public function employees()
    {
        return $this->hasMany(Employee::class,'designation_id');
    }
 
}

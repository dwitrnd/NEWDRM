<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlyLeave extends Model
{
    use HasFactory;

    public $fillable = [
        'version',
        'organization_id',
        'leave_type_id',
        'days',
        'status',
        'leave_year'
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function leaveType()
    {
        return $this->belongsTo(leaveType::class);
    }
}

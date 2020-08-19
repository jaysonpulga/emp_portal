<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeActivity extends Model
{
    //
    protected $fillable = [
        'user_id',
        'transact_date',
        'hour_from',
        'hour_to',
        'planned_activity',
        'actual_activity',
        'status',
    ];
}

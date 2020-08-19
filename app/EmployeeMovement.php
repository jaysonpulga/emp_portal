<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeMovement extends Model
{
    //
    protected $fillable = [
        'user_id',
        'transact_date',
        'places',
        'people',
        'modeoftranspo'
    ];
}

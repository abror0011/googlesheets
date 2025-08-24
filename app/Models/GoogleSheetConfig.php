<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleSheetConfig extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];
}

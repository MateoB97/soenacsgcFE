<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tools;
use Illuminate\Support\Facades\DB;

class general extends Model
{
    protected $fillable=[
        'masterToken'
    ];

    public function getDateFormat()
    {
        return Tools::dateTimeSql();
    }
}

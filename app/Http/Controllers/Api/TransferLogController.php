<?php

namespace App\Http\Controllers\Api;

use App\Models\TransferLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransferLogController extends Controller
{
    public static function add($admin,$source,$destination,$montant){   
        TransferLog::create([
            'admin'=>$admin,
            'source'=>$source,
            'destination'=>$destination,
            "montant"=>$montant
        ]);
    }
}
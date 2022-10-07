<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller{
    public function store(Request $request) {
        $deposit = new Deposit;
        $deposit->user_id = $request->user_id;
        $deposit->deposit_weight = $request->deposit_weight; 
        $deposit->amount = $request->amount; 
        $deposit->save();
 
        return $this->sendResponse([
            "message" => "Deposit succesfully saved"
        ]);
    }
 
}

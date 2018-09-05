<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class LipaNaMpesaOnlineController extends Controller
{
     public function index()
     {

       $callbackData = json_decode(trim(file_get_contents('php://input')));
        
       Transaction::where('third_party_trans_id', '=', $callbackData->Body->stkCallback->CheckoutRequestID)
          ->update(['result_code' => $callbackData->Body->stkCallback->ResultCode,
                    'result_desc' => $callbackData->Body->stkCallback->ResultDesc,
                    'third_party_trans_id' => $callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value ?? $callbackData->Body->stkCallback->CheckoutRequestID ?? NULL,
                    'transaction_time' => date_format(date_create($callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value ?? NULL), "Y-m-d H:i:s") ?? NULL,
                    'status' => 4,
                   ]);
     }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionStatusController extends Controller
{

     public function index()
     {

       $callbackData = json_decode(trim(file_get_contents('php://input')));
        
       Transaction::where('third_party_trans_id', '=', $callbackData->Result->ConversationID)
          ->update([
          	        'result_code' => $callbackData->Result->ResultCode,
                    'result_desc' => $callbackData->Result->ResultDesc,
                    'third_party_trans_id' => $callbackData->Result->TransactionID ?? $callbackData->Result->ConversationID ?? NULL,
                    'transaction_time' => date_format(date_create($callbackData->Result->ResultParameters->ResultParameter[2]->Value ?? NULL), "Y-m-d H:i:s") ?? NULL,
                    'amount' => $callbackData->Result->ResultParameters->ResultParameter[3]->Value ?? NULL,
                    'message' => $callbackData->Result->ResultParameters->ResultParameter[4]->Value ?? NULL,
                    'account_name' => $callbackData->Result->ResultParameters->ResultParameter[5]->Value ?? NULL,
                    'currency' => $callbackData->Result->ResultParameters->ResultParameter[6]->Value ?? NULL,
                    'status' => 4,
                   ]);
     }


}

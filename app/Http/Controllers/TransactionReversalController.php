<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionReversalController extends Controller
{
   public function index(){

        $callbackData = json_decode(file_get_contents('php://input'));

        Transaction::where('third_party_trans_id', '=', $callbackData->Result->ConversationID)
          ->update(['result_code' => $callbackData->Result->ResultCode,
                    'result_desc' => $callbackData->Result->ResultDesc,
                    'third_party_trans_id' => $callbackData->Result->TransactionID ?? $callbackData->Result->ConversationID ?? NULL,
                    'transaction_time' => date("Y-m-d H:i:s"),
                    'status' => 4,
                   ]);
   }
}

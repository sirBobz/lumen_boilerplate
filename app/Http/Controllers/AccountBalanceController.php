<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class AccountBalanceController extends Controller
{

    public function index()
    {
        $callbackData=json_decode(file_get_contents('php://input'));

        Transaction::where('third_party_trans_id', $callbackData->Result->ConversationID)
        ->update(
            [
              'transaction_time' => date('Y-m-d') ?? NULL,
              'result_code' => $callbackData->Result->ResultCode,
              'result_desc' => $callbackData->Result->ResultDesc,
              'third_party_trans_id' => $callbackData->Result->TransactionID ?? $callbackData->Result->ConversationID ?? NULL,
              'account_balance'=> $callbackData->Result->ResultParameters->ResultParameter[0]->Value ?? NULL,
              'status' => 5,
            ]);
    }
}

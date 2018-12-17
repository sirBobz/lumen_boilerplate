<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class BusinessToBusinessController extends Controller
{
	public function index()
    {

       $callbackData=json_decode(file_get_contents('php://input'));

                if ($callbackData->Result->ResultCode == 0)
                {

                    $transaction_time = date_format(date_create($callbackData->Result->ResultParameters->ResultParameter[4]->Value ?? NULL), "Y-m-d H:i:s") ?? NULL;
                    $amount = $callbackData->Result->ResultParameters->ResultParameter[1]->Value ?? NULL;
                    $customer_name = $callbackData->Result->ResultParameters->ResultParameter[5]->Value ?? NULL;
                    $account_balance = $callbackData->Result->ResultParameters->ResultParameter[3]->Value ?? NULL;
                    $currency = $callbackData->Result->ResultParameters->ResultParameter[0]->Value ?? NULL;

                }
        
               Transaction::where('third_party_trans_id', '=', $callbackData->Result->ConversationID)
                 ->update([
                    'result_code' => $callbackData->Result->ResultCode,
                    'result_desc' => $callbackData->Result->ResultDesc,
                    'third_party_trans_id' => $callbackData->Result->TransactionID ?? $callbackData->Result->ConversationID ?? NULL,
                    'transaction_time' => $transaction_time ?? date('Y-m-d H:i:s'),
                   // 'amount' => $amount ?? NULL,
                    'customer_name' => $customer_name ?? NULL ,
                    'account_balance' => $account_balance ?? NULL,
                    'currency' => $currency ?? NULL,
                    'status' => 5,
                   ]);

    }
  
}

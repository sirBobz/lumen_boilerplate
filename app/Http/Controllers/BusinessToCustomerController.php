<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Transaction;

class BusinessToCustomerController extends Controller
{
	 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->Json_object = trim(file_get_contents('php://input'));
    }

	 /**
     * receives B2C response from telco
     *
     * @return void
     */
    public function index()
    {
    	 
         $Json_result = json_decode($this->Json_object);

         $this->saveResponse($Json_result);
    }


    public function saveResponse($Json_result)
    {
        $B2CWorkingAccountAvailableFunds = "";
        $TransactionCompletedDateTime = "";
        $ReceiverPartyPublicName = "";

        if ($Json_result->Result->ResultCode == 0) 
         {
           $data = json_decode(json_encode(json_decode($Json_object)->Result), true);

          foreach ($data['ResultParameters']['ResultParameter'] as $parameter) 
          {

                 if ($parameter['Key'] === 'TransactionAmount')
                          {
                          $Amount = $parameter['Value'];
                          }

                  if ($parameter['Key'] === 'TransactionReceipt')
                          {
                          $TransactionReceipt = $parameter['Value'];
                          }

                  if ($parameter['Key'] === 'ReceiverPartyPublicName')
                          {
                          $ReceiverPartyPublicName = $parameter['Value'];
                          }

                  if ($parameter['Key'] === 'TransactionCompletedDateTime')
                          {
                          $TransactionCompletedDateTime = $parameter['Value'];
                          }

                  if ($parameter['Key'] === 'B2CUtilityAccountAvailableFunds')
                          {
                          $B2CUtilityAccountAvailableFunds = $parameter['Value'];
                          }

                  if ($parameter['Key'] === 'B2CWorkingAccountAvailableFunds')
                          {
                          $B2CWorkingAccountAvailableFunds = $parameter['Value'];
                          }

                  if ($parameter['Key'] === 'B2CRecipientIsRegisteredCustomer')
                          {
                          $B2CRecipientIsRegisteredCustomer = $parameter['Value'];
                          }

                  if ($parameter['Key'] === 'B2CChargesPaidAccountAvailableFunds')
                          {
                          $B2CChargesPaidAccountAvailableFunds = $parameter['Value'];
                          }
           }

         }

          Transaction::where('third_party_trans_id', $Json_result->Result->ConversationID)
          ->update([ 
          	          'result_code' => $Json_result->Result->ResultCode,
                      'result_desc' => $Json_result->Result->ResultDesc,
                      'account_balance' => $B2CWorkingAccountAvailableFunds,
                      'customer_name' => $ReceiverPartyPublicName,
                      'third_party_trans_id' => $Json_result->Result->TransactionID,
                      'transaction_time' => date_format(date_create($TransactionCompletedDateTime), "Y-m-d H:i:s"),
                      'status' => 5,
                  ]);

    }
}

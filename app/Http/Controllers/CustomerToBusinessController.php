<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Traits\getOrganizationID;
use App\Traits\ServiceID;
use App\Models\Callback;

class CustomerToBusinessController extends Controller
{
    use ServiceID, getOrganizationID;

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->request_id = "Dibon" . preg_replace('/\D/', '', date("Y-m-d H:i:s", explode(" ", microtime())[1]) . substr((string)explode(" ", microtime())[0],1,4)) . str_random(10);
    }

    /**
     * receives C2B confirmation
     *
     * @return 
     */
   public function index()
   {
           $request=json_decode(file_get_contents('php://input'));

           $transaction = new Transaction;
           $transaction->third_party_trans_id = $request->TransID;
           $transaction->amount = $request->TransAmount;
           $transaction->account = $request->BusinessShortCode;
           $transaction->account_name = $request->BillRefNumber;
           $transaction->account_balance = $request->OrgAccountBalance;
           $transaction->phone_number = $request->MSISDN;
           $transaction->customer_name = $request->FirstName $request->MiddleName $request->LastName;
           $transaction->org_id = $this->getOrganizationID($request);
           $transaction->service_id = $this->getC2bServiceID();
           $transaction->request_id = $this->request_id;
           $transaction->status = 5;
           $transaction->save();


   }

    /**
     * receives C2B validation request
     *
     * @return void
     */
   public function validation()
   {
      $request=json_decode(file_get_contents('php://input'));

      $validation_url = Callback::where('service_id', '=', $this->getC2bServiceID())
                          ->where('org_id', $this->getOrganizationID($request))
                          ->value('validation_url');
                  
      if ($validation_url === null) {

         response()->json(array
                  (
                   'ResultCode' => '0',
                   'ResultDesc' => "Validation passed successfully",
                   'request_data' => $request->toArray(),
                  ), 200)->send();
      }
      else
      {
         $this->postToValidationUrl($request, $validation_url);
      }

   }


    /**
     * post C2B Validation URL
     *
     * @return void
     */
   public function postToValidationUrl($request, $validation_url)
   {

    try
    {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $validation_url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); 

      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));

      $curl_response = curl_exec($curl);

      echo $curl_response;

      Log::info("Data " . json_encode($request) . " URL " . $validation_url . " Response " . $curl_response);

    }
    catch(Exception $exc)
    {
       Log::error($exc);
    }
    
    }
   
}

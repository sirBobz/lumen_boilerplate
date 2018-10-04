<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Traits\OrganizationID;
use App\Traits\ServiceID;
use App\Models\Callback;
use App\Models\TransactionRate;
use App\Models\OrganizationsFloat;
use App\Models\OrganizationAccount;
use Exception, Log;

class CustomerToBusinessController extends Controller
{
    use ServiceID, OrganizationID;

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
      try 
      {
           $request = json_decode(file_get_contents('php://input'));

           $this->checkIfAccountHasFloat($request);

           $transaction = new Transaction;
           $transaction->third_party_trans_id = $request->TransID;
           $transaction->amount = $request->TransAmount;  
           $transaction->transaction_time = date_format(date_create($request->TransTime), "Y-m-d H:i:s");
           $transaction->account = $request->BusinessShortCode;
           $transaction->account_name = $request->BillRefNumber;
           $transaction->account_balance = $request->OrgAccountBalance;
           $transaction->phone_number = $request->MSISDN;
           $transaction->customer_name = $request->FirstName . " " . $request->MiddleName  . " " . $request->LastName;
           $transaction->org_id = $this->getOrganizationID($request);
           $transaction->service_id = $this->getServiceID("mpesa_c2b");
           $transaction->request_id = $this->request_id;
           $transaction->status = 5;
           $transaction->save();

           echo '{"ResultCode":0,"ResultDesc":"Confirmation recieved successfully"}';
      } 
      catch (Exception $exc) 
      {
         Log::error($exc);  
      }


   }

    /**
     * receives C2B validation request
     *
     * @return void
     */
  public function validation()
   {
    try{


        $request = json_decode(file_get_contents('php://input'));

        $this->checkIfAccountHasFloat($request);

        $validation_url = Callback::where('service_id', '=', $this->getServiceID("mpesa_c2b"))
                            ->where('org_id', $this->getOrganizationID($request))
                            ->value('validation_url');
                    
        if ($validation_url === NULL) 
          {

            echo '{"ResultCode":0,"ResultDesc":"Validation passed successfully"}';

          }
        else
          {
            $this->postToValidationUrl($request, $validation_url);
          }

      } 
    catch (Exception $exc) 
      {
         Log::error($exc);  
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


  /**
   * get billing rate
   *
   * @return bolean
  */
  public function getBillingRate($request)
  {
      $rate = TransactionRate::where('org_id', $this->getOrganizationID($request))
                           ->where('service_id', '=', $this->getServiceID("mpesa_c2b"))
                           ->value('rate');
      if ($rate < 1) 
      {
        $rate = $rate * $request->TransAmount;
      }

     return $rate;
  }


  /**
   * Check If Account Has Float
   *
   * @return bolean
  */
  public function checkIfAccountHasFloat($request)
  {

    if( $this->getBillingType($request) === 'postpaid')
     {

     }
    else
     {
      if ( $this->getBillingRate($request) <= OrganizationsFloat::where('org_id', '=', $this->getOrganizationID($request))->value('available_balance') )
        {

        }
       else
        {
          exit("Insufficient Account Float");
        }
     }

  }


  public function getBillingType($request)
  {
      return OrganizationAccount::where('org_id', '=', $this->getOrganizationID($request))
                                       ->where('service_id', '=', $this->getServiceID("mpesa_c2b"))
                                       ->where('account', '=', $request->BusinessShortCode)
                                       ->value('billing_type');
  }
   
}

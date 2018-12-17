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

       $this->request = json_decode(file_get_contents('php://input'));
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
           $this->checkIfAccountHasFloat();

           $transaction = new Transaction;
           $transaction->third_party_trans_id = $this->request->TransID;
           $transaction->amount = $this->request->TransAmount;  
           $transaction->transaction_time = date_format(date_create($this->request->TransTime), "Y-m-d H:i:s");
           $transaction->account = $this->request->BusinessShortCode;
           $transaction->account_name = $this->request->BillRefNumber;
           $transaction->account_balance = $this->request->OrgAccountBalance;
           $transaction->phone_number = $this->request->MSISDN;
           $transaction->customer_name = $this->request->FirstName . " " . $this->request->MiddleName  . " " . $this->request->LastName;
           $transaction->org_id = $this->getOrganizationID($this->request);
           $transaction->service_id = $this->getServiceID("mpesa_c2b");
           $transaction->request_id = $this->request_id;
           $transaction->result_desc = "Processed Sucessfully";
           $transaction->status = 5;
           $transaction->save();

           $response = array
               (
                  'ResultCode' => 0,
                  'ResultDesc' => "Confirmation recieved successfully"
               );
           response()->json($response, 200)->send();

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
    try
    {
        $this->checkIfAccountHasFloat();

        $validation_url = Callback::where('org_id', $this->getOrganizationID($this->request))
                                  ->where('service_id', '=', $this->getServiceID("mpesa_c2b"))
                                  ->value('validation_url');
                    
        if ($validation_url === NULL) 
          {

                $response = array
                    (
                      'ResultCode' => 0,
                      'ResultDesc' => "Validation passed successfully",
                    );

                response()->json($response, 200)->send();
          }
        else
          {
            $this->postToValidationUrl($validation_url);
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
  public function postToValidationUrl($validation_url)
   {
    try
     {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $validation_url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->request));
        $curl_response = curl_exec($curl);

        Log::info("Data " . json_encode($this->request) . " URL " . $validation_url . " Response " . $curl_response);

       return $curl_response;

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
  public function getBillingRate()
  {
      $rate = TransactionRate::where('org_id', $this->getOrganizationID($this->request))
                           ->where('service_id', '=', $this->getServiceID("mpesa_c2b"))
                           ->value('rate');
      if ($rate < 1) 
      {
        $rate = $rate * $this->request->TransAmount;
      }

     return $rate;
  }


  /**
   * Check If Account Has Float
   *
   * @return bolean
  */
  public function checkIfAccountHasFloat()
  {
    try
      {
        if( $this->getBillingType() !=  'postpaid')
         {
          if ( $this->getBillingRate() >= OrganizationsFloat::where('org_id', '=', $this->getOrganizationID($this->request))->value('available_balance') )
            {
              Log::info("Insufficient Account Float For Request: " . json_encode($this->request));

              exit("Insufficient Account Float");
            }
         }
      }
    catch(Exception $exc)
      {
        Log::error($exc);
      } 

  }

  /**
   * Get the Billing type
   *
   * @return billing type
  */
  public function getBillingType()
  {
      return OrganizationAccount::where('org_id', '=', $this->getOrganizationID($this->request))
                                       ->where('service_id', '=', $this->getServiceID("mpesa_c2b"))
                                       ->where('account', '=', $this->request->BusinessShortCode)
                                       ->value('billing_type');
  }
   
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\OrganizationAccount;

class CustomerToBusinessController extends Controller
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->request_id = "Dibon" . preg_replace('/\D/', '', date("Y-m-d H:i:s", explode(" ", microtime())[1]) . substr((string)explode(" ", microtime())[0],1,4)) . str_random(10);
    }

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
           $transaction->org_id = OrganizationAccount::where('account', $request->BusinessShortCode)->where('service_id', Service::where('name', '=', 'mpesa_c2b')->value('id'))->value('org_id');
           $transaction->service_id = Service::where('name', '=', 'mpesa_c2b')->value('id');
           $transaction->request_id = $this->request_id;
           $transaction->status = 4;
           $transaction->save();


   }
        
}

<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/





$router->group(['prefix' => 'api/v1'], function() use ($router) {
  
    $router->post('/b2c/payment-notification','BusinessToCustomerController@index');

    $router->post('/c2b/payment-notification','CustomerToBusinessController@index');

    $router->post('/c2b/payment-validation','CustomerToBusinessController@validation');

    $router->post('/stk-push/payment-notification','LipaNaMpesaOnlineController@index');

    $router->post('/account-balance/notification','AccountBalanceController@index');

    $router->post('/b2b/payment-notification','BusinessToBusinessController@index');

    $router->post('/transaction-reversal/notification','TransactionReversalController@index');

    $router->post('/transaction-status/notification','TransactionStatusController@index');

    
});
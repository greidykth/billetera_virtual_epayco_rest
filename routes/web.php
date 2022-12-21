<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

/** 
 * Clients routes
 */
$router->group(['prefix' => 'v1/clients'], function () use ($router) {
    $router->post('/login', 'ClientController@login');
    $router->post('/registerClient', 'ClientController@registerClient');
});

/** 
 * Transactions routes
 */
$router->group(['prefix' => 'v1/transaction'], function () use ($router) {
    $router->post('/depositWallet', 'TransactionController@depositWallet');
    $router->post('/checkBalance', 'TransactionController@checkBalance');
    $router->post('/payPurchase', 'TransactionController@payPurchase');
    $router->post('/payConfirmation', 'TransactionController@payConfirmation');
});
<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use ApiResponse;
    private $service;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $wsdl = env('URL_SOAP_SERVICE'). '/zoap/transaction/server?wsdl';
        $this->service = $this->createSoapClientInstance($wsdl);
    }

     /**
     * Create an instance of transaction, require dni, cellphone, value
     *
     * @param int $dni
     * @param int $cellphone
     * @param int $value
     * @return object
     */
    public function depositWallet(Request $request)
    {
        try {
            return $this->soapApiResponse(
                $this->service->depositWallet(
                    $request->all()),
                "depositWalletResult"
            );
        } catch (\Throwable $th) {
            return $this->defaultResponse(
                false,
                422,
                "Algo ha fallado durante la recarga",
                ['error' => $th],
            );
        }
        
    }
    
    /**
     * Create an instance of transaction, require dni, cellphone
     *
     * @param int $dni
     * @param int $cellphone
     * @return object
     */
    public function checkBalance(Request $request)
    {
        try {
            return $this->soapApiResponse(
                $this->service->checkBalance(
                    $request->all()),
                "checkBalanceResult"
            );
        } catch (\Throwable $th) {
            return $this->defaultResponse(
                false,
                422,
                "Algo ha fallado durante la consulta",
                ['error' => $th],
            );
        }
    }

     /**
     * Pay purchase, require dni, cellphone, value and token
     *
     * @param int $dni
     * @param int $cellphone
     * @param int $value
     * @param string $token
     * @return object
     */
    public function payPurchase(Request $request)
    {
        try {
            return $this->soapApiResponse(
                $this->service->payPurchase(
                    $request->all()),
                "payPurchaseResult"
            );
        } catch (\Throwable $th) {
            return $this->defaultResponse(
                false,
                422,
                "Algo ha fallado durante el pago",
                ['error' => $th],
            );
        }
    }

    /**
     * Pay confirmation, require token and tokenConfirmation
     *
     * @param string $tokenConfirmation
     * @param string $token
     * @return object
     */
    public function payConfirmation(Request $request)
    {
        try {
            return $this->soapApiResponse(
                $this->service->payConfirmation(
                    $request->all()),
                "payConfirmationResult"
            );
        } catch (\Throwable $th) {
            return $this->defaultResponse(
                false,
                422,
                "Algo ha fallado durante la confirmación del pago",
                ['error' => $th],
            );
        }
    }
}

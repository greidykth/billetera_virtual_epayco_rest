<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
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
        $wsdl = 'http://billetera-virtual-soap.test/zoap/client/server?wsdl';
        $this->service = $this->createSoapClientInstance($wsdl);
    }

    /**
     * Create an instance of client, requiere dni, name, email, cellphone.
     *
     * @param int $dni
     * @param string $name
     * @param string $email
     * @param int $cellphone
     * @return object
     */
    public function registerClient(Request $request)
    {
        try {
            //code...
            return $this->soapApiResponse(
                $this->service->registerClient(
                    $request->all()),
                "registerClientResult"
            );
        } catch (\Throwable $th) {
            return $this->defaultResponse(
                false,
                422,
                "Algo ha fallado durante el registro",
                ['error' => $th],
            );
        }
        
    }

    /**
     * Login client, require dni and cellphone
     *
     * @param int $dni
     * @param int $cellphone
     * @return object CustomApiResponser
     */
    public function login(Request $request)
    {
        try {
            return $this->soapApiResponse(
                $this->service->login(
                    $request->all()),
                "loginResult"
            );
        } catch (\Throwable $th) {
            return $this->defaultResponse(
                false,
                422,
                "Algo ha fallado durante el login",
                ['error' => $th]
            );
        }
    }
}

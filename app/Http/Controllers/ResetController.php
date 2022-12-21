<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;

class ResetController extends Controller
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
        $wsdl = 'http://billetera-virtual-soap.test/zoap/reset/server?wsdl';
        $this->service = $this->createSoapClientInstance($wsdl);
    }

    /**
     * Reset tables
     *
     * @return string
     */
    public function reset()
    {
        try {
            return $this->soapApiResponse(
                $this->service->reset(),
                "resetResult"
            );
        } catch (\Throwable $th) {
            return $this->defaultResponse(
                false,
                422,
                "Algo ha fallado",
                ['error' => $th],
            );
        }
    }
}

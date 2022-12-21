<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use SoapClient;

class Controller extends BaseController
{
    //Crea una instancia de soap client
    public function createSoapClientInstance($wsdl)
    {
        return new SoapClient($wsdl, [
            'stream_context' =>   stream_context_create([
                'http' => [
                    'user_agent' => 'PHPSoapClient'
                ]
            ]),
            'cache_wsdl'     => WSDL_CACHE_NONE
        ]);
    }
}

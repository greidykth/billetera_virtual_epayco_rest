<?php

namespace App\Traits;

trait ApiResponse
{
    public function soapApiResponse($content, $nameResult)
    {

        return response(json_encode($content->{$nameResult}))->header('Content-Type', 'application/json');
    }

    public function defaultResponse($success, $code = 00, $message = "", $data = null)
    {
        return response(
            [
                "success" => $success,
                "cod_error" => $code,
                "message_error" => $message,
                "data" => $data,
            ]
        )->header('Content-Type', 'application/json');
    }

    public function responseNoData($success, $message = "", $code = 00)
    {
        return response(
            [
                "success" => $success,
                "cod_error" => $code,
                "message_error" => $message,
            ],
        )->header('Content-Type', 'application/json');
    }

    public function errorResponse($message, $code)
    {
        return response([
            "success" => false,
            "cod_error" => $code,
            "message_error" => $message,
            ]);
    }

}
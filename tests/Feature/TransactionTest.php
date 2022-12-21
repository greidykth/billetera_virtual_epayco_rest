<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TransactionTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_should_deposit_money_in_a_wallet()
    {
        
        $response = $this->post('/v1/transactions/depositWallet', [
            'dni' => "18127555",
            'cellphone' => "47092339",
            'value' => "100"
        ]);

        $response->seeJsonContains([
            "message_error" => "Recarga de saldo realizada satisfactoriamente",
            "cod_error" => "00"])
            ->seeJsonStructure([
                "success",
                "cod_error",
                "message_error",
                "data" => [
                    "value",
                    "balance"
                ]
        ]);

        //Test con datos incompletos
        $wrongData = $this->post('/v1/transactions/depositWallet', [
            'dni' => "18127555",
            'cellphone' => "",
            'value' => "100"
        ]);

        $wrongData->seeJsonContains([
            "cod_error" => "401",
            "message_error" => "Las credenciales no son correctas",
        ]);
    }

    public function test_should_check_balance()
    {
        
        $response = $this->post('/v1/transactions/checkBalance', [
            'dni' => "18127555",
            'cellphone' => "47092339",
        ]);

        $response->seeJsonContains([
            "message_error" => "Consulta de saldo realizada satisfactoriamente",
            "cod_error" => "00"])
            ->seeJsonStructure([
                "success",
                "cod_error",
                "message_error",
                "data" => [
                    "balance"
                ]
        ]);

        //Test con datos incompletos
        $wrongData = $this->post('/v1/transactions/checkBalance', [
            'dni' => "18127555",
            'cellphone' => "",
        ]);

        $wrongData->seeJsonContains([
            "cod_error" => "401",
            "message_error" => "Las credenciales no son correctas",
        ]);
    }

    public function test_should_make_a_payment_request_and_returns_a_token_confirmation()
    {
        //Test con token incorrecto
        $wrongData = $this->json('POST', '/v1/transactions/payPurchase', [
            'dni' => "18127555",
            'cellphone' => "47092339",
            "value" => 10,
            "token" => "000"
        ]);

        $wrongData->seeJsonContains([
            "cod_error" => "401",
            "message_error" => "Las credenciales del cliente pagador no son correctas",
        ]);

        $login = $this->post('/v1/clients/login', [
            'dni' => "18127555",
            'cellphone' => "47092339",
        ]);

        $token = json_decode($login->response->getContent(), true)["data"]["token"];

        $response = $this->json('POST', '/v1/transactions/payPurchase', [
            'dni' => "18127555",
            'cellphone' => "47092339",
            "value" => 10,
            "token" => $token
        ]);

        $response
            ->seeJsonContains([
                "success" => "true",
                "cod_error" => "00",
                "message_error" => "Solicitud de pago realizada satisfactoriamente. Se ha enviado un token de confirmación a su email",
            ])
            ->seeJsonStructure(['data' => [
                'token_confirmacion' //se recibe el codigo de confirmación para fines de testing
            ]]);
    }

    public function test_should_confirm_the_payment_when_the_client_send_token_confirmation()
    {
        $login = $this->post('/v1/clients/login', [
            'dni' => "18127555",
            'cellphone' => "47092339",
        ]);

        $token = json_decode($login->response->getContent(), true)["data"]["token"];

        $responsePayment = $this->json('POST', '/v1/transactions/payPurchase', [
            'dni' => "18127555",
            'cellphone' => "47092339",
            "value" => 10,
            "token" => $token
        ]);

        $tokenConfirmation = json_decode(
            $responsePayment->response->getContent(),
            true
        )["data"]["token_confirmacion"];

        $responseConfirmation = $this->json('POST', '/v1/transactions/payConfirmation', [
            "token" => $token,
            "tokenConfirmation" => $tokenConfirmation
        ]);

        $responseConfirmation
            ->seeJsonContains([
                "success" => "true",
                "cod_error" => "00",
                "message_error" => "El pago se realizó satisfactoriamente",
            ])
            ->seeJsonStructure(['data' => [
                'saldo_disponible'
            ]]);
    }

    public function test_should_cancel_transaction_when_the_client_send_an_invalid_token_confirmation()
    {
        $login = $this->post('/v1/clients/login', [
            'dni' => "18127555",
            'cellphone' => "47092339",
        ]);

        $token = json_decode($login->response->getContent(), true)["data"]["token"];

        $responsePayment = $this->json('POST', '/v1/transactions/payPurchase', [
            'dni' => "18127555",
            'cellphone' => "47092339",
            "value" => 10,
            "token" => $token
        ]);

        $responseConfirmation = $this->json('POST', '/v1/transactions/payConfirmation', [
            "token" => $token,
            "tokenConfirmation" => 123
        ]);

        $responseConfirmation
            ->seeJsonContains([
                "success" => "false",
                "cod_error" => "401",
                "message_error" => "Falló la confirmación. Transacción cancelada",
            ]);
    }
}

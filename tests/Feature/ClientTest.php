<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ClientTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_should_register_a_client()
    {
        //test con data incompleta
        $wrongData = $this->post('/v1/clients/registerClient', [
            'dni' => "",
            'name' => "Jose Perez",
            'email' => "josep@gmail.com",
            'cellphone' => "89023456",
        ]);

        $wrongData->seeJsonContains(["cod_error" => "422", "string" => "The dni must have at least 8 digits."]);

        $response = $this->post('/v1/clients/registerClient', [
            'dni' => "18127555",
            'name' => "Jose Lopez",
            'email' => "josel@gmail.com",
            'cellphone' => "47092339",
        ]);

        $response->seeJsonContains(["name" => "Jose Lopez", "cod_error" => "0"])
            ->seeJsonStructure([
                "success",
                "cod_error",
                "message_error",
                "data" => [
                    "client" => [
                        "dni",
                        "name",
                        "email",
                        "cellphone"
                    ]
                ]
        ]);
    }

    public function test_should_login_a_client()
    {
        //test con data incompleta
        $wrongData = $this->post('/v1/clients/login', [
            'dni' => "18127555",
            'cellphone' => "",
        ]);
        $wrongData->seeJsonContains(["cod_error" => "401", "message_error" => "Las credenciales proporcionadas son inválidas"]);


        $response = $this->post('/v1/clients/login', [
            'dni' => "18127555",
            'cellphone' => "47092339",
        ]);

        $response->seeJsonContains(["name" => "Jose Lopez", "cod_error" => "0"])
            ->seeJsonStructure([
                "success",
                "cod_error",
                "message_error",
                "data" => [
                    "client" => [
                        "dni",
                        "name",
                        "email",
                        "cellphone"
                    ],
                    "token",
                    "saldo"
                ]
        ]);
    }
}

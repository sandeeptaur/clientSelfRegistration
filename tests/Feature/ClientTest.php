<?php

namespace Tests\Feature;

use Tests\TestCase;

class ClientTest extends TestCase
{
    public function testClientCreatedSuccessfully()
    {

        $randNum = rand(100, 9999);
        $email = $randNum . 'sandeep@gmail.com';
        $clientData = [
            'name' => 'Sandeep taur',
            'address1' => 'Rack Hover Way',
            'address2' => '6125',
            'city' => 'Sterling',
            'state' => 'VA',
            'country' => 'USA',
            'zipCode' => 20166,
            'phoneNo1' => '555-666-7777',
            'phoneNo2' => '555-666-7777',
            'user' =>
            [
                'firstName' => 'Jahn',
                'lastName' => 'Doe',
                'email' => $email,
                'password' => 'Secret@l23',
                'password_confirmation' => 'Secret@l23',
                'phone' => '123-456-7890',
            ]
        ];
        $this->json('POST', 'api/v1/register', $clientData, ['Accept' => 'application/json'])
            ->assertStatus(201);
    }

    public function testClientListedSuccessfully()
    {
        $this->json('GET', 'api/v1/account', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}

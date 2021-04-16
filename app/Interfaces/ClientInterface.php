<?php

namespace App\Interfaces;


interface ClientInterface
{
    //Get clients
    public function getClients($request);

    //Create client
    public function createClient($request);

}

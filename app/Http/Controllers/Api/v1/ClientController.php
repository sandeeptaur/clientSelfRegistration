<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use App\Interfaces\ClientInterface;
use App\Http\Resources\ClientCollection;


class ClientController extends Controller
{

    protected $clientInteface;

    /**
     * Create a new constructor for this controller
     */
    public function __construct(ClientInterface $clientInteface)
    {
        $this->clientInteface = $clientInteface;
    }

    //Store Client and crete user
    public function store(ClientRequest $request)
    {
        return $this->clientInteface->createClient($request);
    }

    //Get Clients with searching and sorting
    public function index(Request $request)
    {
        $query = $this->clientInteface->getClients($request);
        $perPage = $request->per_page ?? 10;
        $clients = $query->paginate($perPage);
        return new ClientCollection($clients);
    }
}

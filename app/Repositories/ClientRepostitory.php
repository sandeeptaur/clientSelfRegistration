<?php

namespace App\Repositories;


use Illuminate\Http\Response;
use App\Interfaces\ClientInterface;
use App\Traits\ResponseFormatTrait;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use App\Traits\GeoLocation;

class ClientRepository implements ClientInterface
{
    use GeoLocation, ResponseFormatTrait;

    public function getClients($request)
    {
        $sortableCols = [
            'client_name', 'address1', 'address2', 'city', 'state', 'country', 'latitude',
            'longitude', 'phone_no1', 'phone_no2', 'zip', 'start_validity', 'end_validity', 'status', 'created_at',
        ];
        $query = Client::with('user');
        // Filter
        if ($request->has('name')) {
            $query->where('client_name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('address1')) {
            $query->where('address1', 'like', '%' . $request->address1 . '%');
        }
        if ($request->has('address2')) {
            $query->where('address2', 'like', '%' . $request->address2 . '%');
        }
        if ($request->has('address2')) {
            $query->where('address2', 'like', '%' . $request->address2 . '%');
        }
        if ($request->has('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }
        if ($request->has('state')) {
            $query->where('state', 'like', '%' . $request->state . '%');
        }
        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }
        if ($request->has('zipCode')) {
            $query->where('zipCode', 'like', '%' . $request->zipCode . '%');
        }
        if ($request->has('phoneNo1')) {
            $query->where('phone_no1', 'like', '%' . $request->phone_no1 . '%');
        }
        if ($request->has('phoneNo2')) {
            $query->where('phone_no2', 'like', '%' . $request->phone_no2 . '%');
        }

        //Sorting
        if ($request->sortby == 'name') {
            $request->sortby = 'client_name';
        }
        $sortby = $request->sortby ?? 'created_at';
        $orderby = $request->orderby ?? 'desc';
        if (in_array($sortby, $sortableCols)) {
            $query->orderBy($sortby, $orderby);
        }
        return $query;
    }
    //Create client and his account
    public function createClient($requestedData)
    {
        DB::beginTransaction();
        try {
            $userDetails = $requestedData['user'];
            //Get Lat lng of address
            $fullAddress = $requestedData['address1'] . ' ' . $requestedData['address2'] . ', ' . $requestedData['city']  . ', ' . $requestedData['state'] . ', ' . $requestedData['zipCode'] . ' ' . $requestedData['country'];
            $geoAddress = $this->getLocation($fullAddress);
            if (empty($geoAddress)) {
                throw new ValidationException('Address Latitude and Longitude can not empty');
            }

            $clientData =  Client::create(
                [
                    'client_name' => $requestedData['name'],
                    'address1' => $requestedData['address1'],
                    'address2' => $requestedData['address2'],
                    'address2' => $requestedData['address2'],
                    'country' => $requestedData['country'],
                    'state' => $requestedData['state'],
                    'city' => $requestedData['city'],
                    'zip' => $requestedData['zipCode'],
                    'latitude' => $geoAddress['lat'],
                    'longitude' => $geoAddress['lng'],
                    'phone_no1' => $requestedData['phoneNo1'],
                    'phone_no2' => $requestedData['phoneNo2'],
                    'start_validity' => Carbon::now()->toDateString(),
                    'end_validity' => Carbon::now()->addDay(15)->toDateString(),
                ]
            );
            //Create client user account 
            $userData = User::create([
                'client_id' => $clientData->id,
                'first_name' => $userDetails['firstName'],
                'last_name' => $userDetails['lastName'],
                'phone' => $userDetails['phone'],
                'email' => $userDetails['email'],
                'password' => Hash::make($userDetails['password']),
            ]);
            DB::commit();
            //Return with success message
            return $this->success('Client created successfully!', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            //Return with error message
            return $this->errors($e->getMessage() . 'Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }
}

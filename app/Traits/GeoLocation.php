<?php

namespace App\Traits;
use Illuminate\Support\Facades\Redis;

trait GeoLocation
{
	/**
	 * getLocation function
	 *
	 * @param [type] $address
	 * @return location
	 */
    public  function getLocation($address)
    {
		//Check address exist in Redis DB
		$existingAddress = Redis::hgetall($address);
		if($existingAddress){
			return $existingAddress;
		}
       	$client = new \GuzzleHttp\Client();
		$geocoder = new \Spatie\Geocoder\Geocoder($client);
		$geocoder->setApiKey(config('geocoder.key'));
		$location=$geocoder->getCoordinatesForAddress($address);
		//Store Geocode in Redis DB
		Redis::hmset($address, [
            'lat' => $location['lat'],
            'lng' => $location['lng'],
        ]);
		return $location;
    }
}

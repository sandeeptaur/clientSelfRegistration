<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Traits\CustomPagination;

class ClientCollection extends ResourceCollection
{
    use CustomPagination;

    public function toArray($request)
    {
        return [
            'success' => true,
            'data' => $this->collection,
        ];
    }
    public function withResponse($request, $response)
    {
        $jsonResponse = json_decode($response->getContent(), true);
        $pagination = $this->customizePagination($jsonResponse);
        if ($pagination) {
            $jsonResponse['links'] = $pagination['links'];
            $jsonResponse['meta'] = $pagination['meta'];
        }
        $response->setContent(json_encode($jsonResponse));
    }
}

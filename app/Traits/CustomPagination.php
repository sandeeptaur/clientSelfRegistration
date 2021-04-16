<?php

namespace App\Traits;

trait CustomPagination
{
	public function customizePagination($jsonR){
		if(empty($jsonR)){
			return null;
		}
        $links = [
            "path"=>$jsonR['meta']['path'],
            "firstPageUrl"=>$jsonR['links']['first'],
            "lastPageUrl"=>$jsonR['links']['last'],
            "nexPageUrl"=>$jsonR['links']['next'],
            "prevPageUrl"=>$jsonR['links']['prev']
        ];
        $meta = [
            "currentPage"=>$jsonR['meta']['current_page'],
            "from"=>$jsonR['meta']['from'],
            "lastPage"=>$jsonR['meta']['last_page'],
            "perPage"=>$jsonR['meta']['per_page'],
            "to"=>$jsonR['meta']['to'],
            "total"=>$jsonR['meta']['total'],
        ];
		return ['links'=>$links, 'meta'=>$meta];

    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class AnimalApiService
{
  const DOG_API_URL = 'https://api.TheDogAPI.com/v1/';
  const CAT_API_URL = 'https://api.TheCatAPI.com/v1/';

  public function getBreeds($type, $page = null, $limit = null)
  {
    // Set url depending on $type
    $url = ($type === 'cat') ? self::CAT_API_URL : self::DOG_API_URL;

    // Apply x-api-key and get data
    $response = $this->getHeaders($type)
    ->get($url . 'breeds/', [
        'page' => $page ?? '',
        'limit' => $limit ?? '',
    ]);

    // Return json_decoded response body
    return json_decode((string) $response->getBody(), true);
  }


  // Get api request header
  private function getHeaders($type){
    $api_key = ($type == 'cat') ? Config::get('services.cat.key') : Config::get('services.dog.key');

    return Http::withHeaders([
        'Accept'    => 'application/json',
        'x-api-key' => $api_key,
    ]);
  }
}

?>
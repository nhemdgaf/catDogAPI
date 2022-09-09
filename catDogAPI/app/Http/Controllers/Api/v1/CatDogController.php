<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\AnimalApiService;
use Illuminate\Http\Request; 
use Illuminate\Http\JsonResponse;

class CatDogController extends Controller
{
    protected $animalAPIService;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(AnimalApiService $animalApiService)
    {
        $this->animalAPIService = $animalApiService;
    }

     /**
     * Display list of breeds for the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBreeds(Request $request){

        // Get request parameters
        $page   = $request->query('page');
        $limit  = $request->query('limit');

        /**
         * Get dog and cat api result
         * If number is not divisble by 2, get the rounded-up value for dogResult
         */ 
        $dogResult = $this->animalAPIService->getBreeds('dog', $page, ceil($limit/2));
        $catResult = $this->animalAPIService->getBreeds('cat', $page, $limit/2);

        // Merge two arrays
        $result = array_merge($dogResult, $catResult);
        
        // Sort the array by name in ascending order
        usort($result, function ($item1, $item2) {
            return strcasecmp($item1['name'], $item2['name']);
        });

        // Return new json api response
        return new JsonResponse([
                'page' => $page,
                'limit' => $limit,
                'results' => $result
            ], 200);
    }
}

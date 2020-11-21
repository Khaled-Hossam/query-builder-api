<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GeneratingQueryFormRequest;
use App\Services\GeneratingQueryService;

class QueryController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\GeneratingQueryFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        GeneratingQueryFormRequest $request,
        GeneratingQueryService $generatingQueryService
    )
    {
        $result = $generatingQueryService->execute($request->validated());

        if($result == false){
            return response()->json([
                'status' => 'error',
                'message' => 'file doesn\'t have valid data'
            ], 422);
        }
        
        return response()->json($result);
    }
}

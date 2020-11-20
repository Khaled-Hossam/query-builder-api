<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratingQueryFormRequest;
use App\Services\GeneratingQueryService;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        GeneratingQueryFormRequest $request,
        GeneratingQueryService $generatingQueryService
    )
    {
        // dd($request->validated());
        // $content = file_get_contents($request->file('input')->getRealPath() );
        // dd(json_decode( $content, true) );
        $result = $generatingQueryService->execute($request->validated());
        return response()->json($result);
    }
}

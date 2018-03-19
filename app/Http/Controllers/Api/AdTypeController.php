<?php

namespace App\Http\Controllers\Api;

use App\Models\AdType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adTypes = AdType::all();
        $data = fractal()
        ->collection($adTypes, new \App\Transformers\AdTypeTransformer, 'ad_types')
        ->toArray();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdType  $adType
     * @return \Illuminate\Http\Response
     */
    public function show(AdType $adType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdType  $adType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdType $adType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdType  $adType
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdType $adType)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Hotel;
use Illuminate\Http\Request;

class HotelsController extends Controller
{
    /*
     * get the best hotels
     */
    public function getBestHotels(Request $request)
    {
        return Hotel::mapBestHotels($request->all());
    }


    /*
     * get the top hotels
     */
    public function getTopHotels(Request $request)
    {
        return Hotel::mapTopHotels($request->all());
    }

    /*
     * the aggregator API
     */
    public function hotels(Request $request){
        return Hotel::aggregateHotels($request->all());
    }
}

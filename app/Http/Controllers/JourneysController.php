<?php

namespace App\Http\Controllers;

use App\Services\WimtApi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\Journey as JourneyResource;

class JourneysController extends Controller
{
    public function show(Request $request)
    {
        $wimt_api = new WimtApi();
        $from_lat = $request->get('from')[0];
        $from_long = $request->get('from')[1];
        $to_lat = $request->get('to')[0];
        $to_long = $request->get('to')[1];
        $maxItineraries = $request->get('maxItineraries', 5);
        $journey = $wimt_api->getJourney(
            ['lat' => $from_lat, 'long' =>$from_long],
            ['lat' => $to_lat, 'long' => $to_long],
            $maxItineraries
        );
        return new JourneyResource($journey);
    }

    public function showGet(Request $request)
    {
        $wimt_api = new WimtApi();
        $from_lat = $request->get('from_lat');
        $from_long = $request->get('from_long');
        $to_lat = $request->get('to_lat');
        $to_long = $request->get('to_long');
        $maxItineraries = $request->get('maxItineraries', 5);
        $journey = $wimt_api->getJourney(
            ['lat' => $from_lat, 'long' =>$from_long],
            ['lat' => $to_lat, 'long' => $to_long],
            $maxItineraries
        );
        return new JourneyResource($journey);
    }

    private function parseDateToString($date)
    {
        return Carbon::parse($date)->toDateTimeString();
    }
}

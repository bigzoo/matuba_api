<?php

namespace App\Http\Controllers;

use App\Services\WimtApi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $itineraries = collect($journey->itineraries)->map(function ($itenerary){
            $legs = collect($itenerary->legs)->map(function ($leg){
                $waypoints = collect($leg->waypoints)->map(function ($waypoint){
                    return [
                        'location_address' => $waypoint->location->address,
                        'location_coordinates' => $waypoint->location->geometry->coordinates,
                        'departureTime' => $this->parseDateToString($waypoint->departureTime),
                        'arrivalTime' => $this->parseDateToString($waypoint->arrivalTime),
                    ];
                });
                $directions = collect($leg->directions)->map(function ($direction){
                    return [
                        'instruction' => $direction->instruction,
                        'distance' => $direction->distance->value.$direction->distance->unit
                    ];
                });
                return [
                    'type' => $leg->type,
                    'behaviour' => $leg->behaviour,
                    'distance' => $leg->distance->value.$leg->distance->unit,
                    'waypoints' => $waypoints,
                    'directions' => $directions
                ];
            });
            return [
                'departure_time' => $this->parseDateToString($itenerary->departureTime),
                'arrival_time' => $this->parseDateToString($itenerary->arrivalTime),
                'total_distance' => $itenerary->distance->value.$itenerary->distance->unit,
                'travel_time' => $itenerary->duration,
                'legs' => $legs
            ];
        });
        return [
            'from' => [
                'latitude' => $journey->geometry->coordinates[0][0],
                'longitude' => $journey->geometry->coordinates[0][1]
            ],
            'to' => [
                'latitude' => $journey->geometry->coordinates[1][0],
                'longitude' => $journey->geometry->coordinates[1][1]
            ],
            'itineraries' => $itineraries
        ];
    }

    private function parseDateToString($date)
    {
        return Carbon::parse($date)->toDateTimeString();
    }
}

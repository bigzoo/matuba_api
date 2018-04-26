<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Journey extends JsonResource
{
    public function toArray($journey)
    {
        $itineraries = collect($journey->itineraries)->map(function ($itenerary) {
            $legs = collect($itenerary->legs)->map(function ($leg) {
                $waypoints = collect($leg->waypoints)->map(function ($waypoint) {
                    return [
                        'location_address' => $waypoint->location->address,
                        'location_coordinates' => $waypoint->location->geometry->coordinates,
                        'departureTime' => $this->parseDateToString($waypoint->departureTime),
                        'arrivalTime' => $this->parseDateToString($waypoint->arrivalTime),
                    ];
                });
                $directions = collect($leg->directions)->map(function ($direction) {
                    return [
                        'instruction' => $direction->instruction,
                        'distance' => $direction->distance->value . $direction->distance->unit
                    ];
                });
                return [
                    'type' => $leg->type,
                    'behaviour' => $leg->behaviour,
                    'distance' => $leg->distance->value . $leg->distance->unit,
                    'waypoints' => $waypoints,
                    'directions' => $directions
                ];
            });
            return [
                'departure_time' => $this->parseDateToString($itenerary->departureTime),
                'arrival_time' => $this->parseDateToString($itenerary->arrivalTime),
                'total_distance' => $itenerary->distance->value . $itenerary->distance->unit,
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

<?php

namespace App\Services;

use App\Setting;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;

class WimtApi
{

    protected $client;

    public function __construct()
    {
        $token = Setting::getValue('wimt_token');
//        if (!$token || Carbon::parse(Setting::getValue('wimt_token_expiry'))->diffInMinutes(Carbon::now()) < 10) {
        $this->setToken();
//        }
        $this->client = new GuzzleClient(['headers' =>
            [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    private function setToken()
    {
        $client = new GuzzleClient();
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $body = [
            'client_id' => env('WIMT_CLIENT_ID'),
            'client_secret' => env('WIMT_CLIENT_SECRET'),
            'grant_type' => 'client_credentials',
            'scope' => 'transportapi:all'
        ];
        $response = $client->request('POST', 'https://identity.whereismytransport.com/connect/token',
            [
                'headers' => $headers,
                'form_params' => $body
            ]
        )->getBody()->getContents();
        $token = json_decode($response)->access_token;
        $expires_at = Carbon::now()->addHour()->toDateTimeString();
        Setting::setValues(
            [
                'wimt_token' => $token,
                'wimt_token_expiry' => $expires_at
            ]
        );
    }

    public function getJourney(array $from, array $to, $maxItineraries)
    {
        $url = 'https://platform.whereismytransport.com/api/journeys';
        $request_body = [
            'geometry' => [
                'type' => 'Multipoint',
                'coordinates' => [
                    [$from['lat'], $from['long']],
                    [$to['lat'], $to['long']]
                ]
            ],
            'maxItineraries' => $maxItineraries
        ];
        $res = $this->client->post($url, ['body' => json_encode($request_body)]);
        return json_decode($res->getBody()->getContents());
    }
}
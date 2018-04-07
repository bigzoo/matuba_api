<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Report as ReportResource;
use App\Report;

class ReportsController extends ApiBaseController
{
    protected $rules = [
        'description' => ['string'],
        'car_plates' => ['string'],
        'road_name' => ['string'],
        'latitude' => ['string'],
        'longitude' => ['string'],
        'user_id' => ['numeric', 'exists:users,id']
    ];

    protected $createRules = [
        'description' => ['required'],
        'user_id' => ['required'],
    ];

    public function __construct(Report $report)
    {
        $this->model = $report;
        $this->query = Report::query();
    }

    public function collection($collection)
    {
        return ReportResource::collection($collection);
    }

    public function resource($resource)
    {
        return new ReportResource($resource);
    }
}

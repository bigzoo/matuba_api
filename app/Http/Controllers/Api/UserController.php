<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\User as UserResource;
use App\User;

class UserController extends ApiBaseController
{
    protected $rules = [
        'email' => ['email'],
        'name' => ['string'],
        'phone_number' => ['digits:10'],
        'nickname' => ['string'],
        'county' => ['string']
    ];

    protected $createRules = [
        'email' => ['required'],
        'name' => ['required'],
    ];

    public function __construct(User $user)
    {
        $this->model = $user;
        $this->query = User::query();
    }

    public function collection($collection)
    {
        return UserResource::collection($collection);
    }

    public function resource($resource)
    {
        return new UserResource($resource);
    }
}

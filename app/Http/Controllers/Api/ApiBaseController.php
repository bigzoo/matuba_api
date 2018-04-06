<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Http\Request;

abstract class ApiBaseController extends Controller
{
    protected $model;

    protected $query;

    protected $rules = [];

    protected $createRules = [];

    protected $updateRules = [];

    protected $messages = [];

    protected $createMessages = [];

    protected $updateMessages = [];

    protected $authorizeKey;

    abstract public function collection($collection);

    abstract public function resource($resource);

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        if ($this->authorizeKey) {
            $this->authorize($this->authorizeKey . '.view', $this->authorizeFor($request));
        }
        return $this->collection($this->query()->paginate($request->per_page));
    }

    public function query()
    {
        return $this->query;
    }

    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Validation Failed', $validator->errors());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        if ($this->authorizeKey) {
            $this->authorize($this->authorizeKey . '.create', $this->authorizeFor($request));
        }
        $this->validate($request, $this->rules('create'), $this->messages('create'));
        $this->model->fill($request->all());
        $this->model->save();
        return $this->resource($this->model)->response()->setStatusCode(201);
    }

    /**
     * @param string $method
     *
     * @return array
     */
    public function rules($method)
    {
        return array_merge($this->rules, $this->{$method . 'Rules'});
    }

    /**
     * @param string $method
     *
     * @return array
     */
    public function messages($method)
    {
        return array_merge($this->messages, $this->{$method . 'Messages'});
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, $id)
    {
        $resource = $this->getResource($request, $id);

        if ($this->authorizeKey) {
            $this->authorize($this->authorizeKey . '.view', [$resource, $this->authorizeFor($request)]);
        }

        return $this->resource($resource);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param          $id
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules('update'), $this->messages('update'));
        $resource = $this->getResource($request, $id);

        if ($this->authorizeKey) {
            $this->authorize($this->authorizeKey . '.update', [$resource, $this->authorizeFor($request)]);
        }
        $resource->update($request->all());

        return $this->resource($resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, $id)
    {
        $resource = $this->getResource($request, $id);

        if ($this->authorizeKey) {
            $this->authorize($this->authorizeKey . '.delete', [$resource, $this->authorizeFor($request)]);
        }

        $resource->delete();

        return response(null, 204);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    protected function getResource(Request $request, $id)
    {
        return $this->query()->findOrFail($id);
    }


    protected function authorizeFor(Request $request)
    {
        return get_class($this->model);
    }

}

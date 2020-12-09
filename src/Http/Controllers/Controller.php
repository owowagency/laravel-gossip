<?php

namespace OwowAgency\Gossip\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;

    /**
     * The model class.
     *
     * @var string
     */
    protected string $modelClass;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->setResourceModelClass();
    }

    /**
     * Create a paginated JSON response from the given paginator and resource class.
     *
     * @param  \Illuminate\Pagination\AbstractPaginator  $paginator
     * @param  string  $resourceClass
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createPaginatedResponse(
        AbstractPaginator $paginator,
        string $resourceClass
    ): JsonResponse {
        $resources = $resourceClass::collection($paginator);

        $paginator = $paginator->setCollection($resources->collection);

        return ok($paginator);
    }

    /**
     * Create a resource response.
     *
     * @param  \Illuminate\Database\Eloquent\Model|mixed  $model
     * @param  string  $resourceConfig
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createResourceResponse(
        $model,
        string $resourceConfig
    ): JsonResponse {
        $resource = config("gossip.resources.$resourceConfig");

        return ok(new $resource($model));
    }

    /**
     * Sets the resource model class.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function setResourceModelClass()
    {
        $request = request();

        // When no request is present, like in terminal, skip.
        if (! $request || ! $request->route()) {
            return;
        }

        $parameters = array_keys($request->route()->parameters());
        $models = config('gossip.models');

        foreach ($parameters as $parameter) {
            if (array_key_exists($parameter, $models)) {
                $this->modelClass = $models[$parameter];
            }
        }
    }

    /**
     * Tries to retrieve the model.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getModel($value): Model
    {
        if ($value instanceof Model) {
            return $value;
        }

        $instance = new $this->modelClass;

        $model = $instance->resolveRouteBinding($value);

        if (is_null($model)) {
            throw new ModelNotFoundException($instance, $value);
        }

        return $model;
    }
}

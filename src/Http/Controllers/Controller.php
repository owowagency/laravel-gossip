<?php

namespace OwowAgency\Gossip\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

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
     * Get the user model based on the user id parameter.
     *
     * @return \App\Models\User
     */
    protected function getUserFromRoute(): Model
    {
        $userId = request()->route()->parameter('user');

        $modelClass = config('gossip.user_model');

        return $modelClass::findOrFail($userId);
    }
}

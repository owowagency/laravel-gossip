<?php

namespace OwowAgency\Gossip\Http\Controllers;

use Illuminate\Http\JsonResponse;
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

        return new JsonResponse($paginator);
    }
}

<?php

namespace OwowAgency\Gossip\Http\Controllers\Conversations\Users;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use OwowAgency\Gossip\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Paginate the messages of the given conversation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, $conversationId): JsonResponse
    {
        $conversation = $this->getModel($conversationId);

        $this->authorize('view', $conversation);

        $users = QueryBuilder::for(config('gossip.models.user'))
            ->allowedFilters([
                'name', 'first_name', 'last_name', 'full_name',
            ])
            ->defaultSort('-created_at')
            ->ofConversation($conversation)
            ->paginate()
            ->appends($request->query());

        return $this->createPaginatedResponse(
            $users,
            config('gossip.resources.user'),
        );
    }
}

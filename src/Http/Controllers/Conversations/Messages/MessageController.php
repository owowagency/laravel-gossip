<?php

namespace OwowAgency\Gossip\Http\Controllers\Conversations\Messages;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use OwowAgency\Gossip\Http\Controllers\Controller;

class MessageController extends Controller
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

        $messages = QueryBuilder::for(config('gossip.models.message'))
            ->allowedFilters([
                'body',
                AllowedFilter::scope('created_after'),
                AllowedFilter::scope('updated_after'),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'updated_at')
            ->with('user', 'users')
            ->ofConversation($conversation)
            ->paginate()
            ->appends($request->query());

        return $this->createPaginatedResponse(
            $messages,
            config('gossip.resources.message'),
        );
    }
}

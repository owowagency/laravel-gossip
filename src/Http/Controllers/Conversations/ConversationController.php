<?php

namespace OwowAgency\Gossip\Http\Controllers\Conversations;

use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use OwowAgency\Gossip\Http\Controllers\Controller;

class ConversationController extends Controller
{
    /**
     * Returns models instances used for the index action.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', [config('gossip.models.conversation')]);

        $conversations = QueryBuilder::for(config('gossip.models.conversation'))
            ->allowedFilters([
                'name',
            ])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'updated_at')
            ->withDefaultRelations(3)
            ->paginate()
            ->appends(request()->query());

        return $this->createPaginatedResponse(
            $conversations,
            config('gossip.resources.conversation'),
        );
    }

    /**
     * Returns the model instance for the show action.
     *
     * @param  int|string  $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($conversationId): JsonResponse
    {
        $conversation = $this->getModel($conversationId);

        $this->authorize('view', $conversation);

        $conversation->load($conversation->getDefaultRelations());

        return $this->createResourceResponse($conversation, 'conversation');
    }
}

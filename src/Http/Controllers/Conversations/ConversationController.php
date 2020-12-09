<?php

namespace OwowAgency\Gossip\Http\Controllers\Conversations;

use Illuminate\Http\JsonResponse;
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

        $conversations = config('gossip.models.conversation')::withDefaultRelations(3)
            ->httpQuery()
            ->paginate();

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

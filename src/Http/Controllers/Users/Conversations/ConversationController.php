<?php

namespace OwowAgency\Gossip\Http\Controllers\Users\Conversations;

use Illuminate\Http\JsonResponse;
use OwowAgency\Gossip\Http\Controllers\Controller;

class ConversationController extends Controller
{
    /**
     * Returns models instances used for the index action.
     *
     * @param  int|string  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($userId): JsonResponse
    {
        $user = $this->getModel($userId);

        $this->authorize('viewConversationsOf', [config('gossip.models.conversation'), $user]);

        $conversations = config('gossip.models.conversation')::withDefaultRelations(3)
            ->ofUser($user)
            ->httpQuery()
            ->paginate();

        return $this->createPaginatedResponse(
            $conversations,
            config('gossip.resources.conversation'),
        );
    }
}

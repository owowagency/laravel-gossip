<?php

namespace OwowAgency\Gossip\Http\Controllers\Users\Conversations;

use Illuminate\Http\JsonResponse;
use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Http\Controllers\Controller;

class ConversationController extends Controller
{
    /**
     * Returns models instances used for the index action.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(int $userId): JsonResponse
    {
        $user = $this->getUserFromRoute();

        $this->authorize('viewConversationsOf', [Conversation::class, $user]);

        $conversations = Conversation::withRelations(3)
            ->ofUser($user)
            ->httpQuery()
            ->paginate();

        return $this->createPaginatedResponse(
            $conversations,
            config('gossip.resources.conversation'),
        );
    }
}

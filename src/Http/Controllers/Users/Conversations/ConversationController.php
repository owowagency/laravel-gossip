<?php

namespace OwowAgency\Gossip\Http\Controllers\Users\Conversations;

use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use OwowAgency\Gossip\Http\Controllers\Controller;
use OwowAgency\Gossip\Models\Conversation;

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

        $conversations = QueryBuilder::for(config('gossip.models.conversation'))
            ->allowedFilters([
                'name',
            ])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at', 'updated_at')
            ->ofUser($user)
            ->withDefaultRelations(3)
            ->paginate()
            ->appends(request()->query());

        return $this->createPaginatedResponse(
            $conversations,
            config('gossip.resources.conversation'),
        );
    }

    /**
     * Leave a conversation
     * 
     * @param  int|string  $conversation
     * @return \Illuminate\Http\JsonResponse
     */
    public function leave($conversationId): JsonResponse
    {
        $conversation = $this->getModel($conversationId);
        $user = auth()->user();

        if($user->conversations->contains($conversation)) {
            $user->conversations()->detach($conversationId);
        } else {
            return unprocessable_entity();
        }

        return no_content();
    }
}

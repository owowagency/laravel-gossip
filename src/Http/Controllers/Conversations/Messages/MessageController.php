<?php

namespace OwowAgency\Gossip\Http\Controllers\Conversations\Messages;

use Illuminate\Http\JsonResponse;
use OwowAgency\Gossip\Models\Message;
use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Http\Controllers\Controller;

class MessageController extends Controller
{
    /**
     * Paginate the messages of the given conversation.
     *
     * @param  int|string  $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke($conversationId): JsonResponse
    {
        $conversation = Conversation::findOrFail($conversationId);

        $this->authorize('view', $conversation);

        $messages = Message::with('user', 'users')
            ->ofConversation($conversation)
            ->httpQuery()
            ->paginate();

        return $this->createPaginatedResponse(
            $messages,
            config('gossip.resources.message'),
        );
    }
}

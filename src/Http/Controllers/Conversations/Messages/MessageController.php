<?php

namespace OwowAgency\Gossip\Http\Controllers\Conversations\Messages;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OwowAgency\Gossip\Models\Message;
use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Managers\MessageManager;
use OwowAgency\Gossip\Http\Controllers\Controller;

class MessageController extends Controller
{
    /**
     * ConversationController constructor.
     */
    public function __construct()
    {
        $this->modelClass = Conversation::class;
    }

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

        $messages = Message::with('user', 'users')
            ->ofConversation($conversation)
            ->httpQuery()
            ->latest()
            ->paginate();

        MessageManager::handleMessageMarking(
            $messages->getCollection(),
            $request->get('mark_messages_as_read', false)
        );

        return $this->createPaginatedResponse(
            $messages,
            config('gossip.resources.message'),
        );
    }
}

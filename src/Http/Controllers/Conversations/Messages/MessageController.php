<?php

namespace OwowAgency\Gossip\Http\Controllers\Conversations\Messages;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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

        $messages = config('gossip.models.message')::with('user', 'users')
            ->ofConversation($conversation)
            ->httpQuery()
            ->latest()
            ->paginate();

        return $this->createPaginatedResponse(
            $messages,
            config('gossip.resources.message'),
        );
    }
}

<?php

namespace OwowAgency\Gossip\Http\Controllers\Messages;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OwowAgency\Gossip\Http\Controllers\Controller;

class MarkAsReadController extends Controller
{
    /**
     * Mark the message as read.
     *
     * @param  int  $messageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(int $messageId): JsonResponse
    {
        return $this->handleMarkingAsRead($messageId, 'markAsRead');
    }

    /**
     * Mark the message as unread.
     *
     * @param  int  $messageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $messageId): JsonResponse
    {
        return $this->handleMarkingAsRead($messageId, 'markAsUnread');
    }

    /**
     * Handle the controller logic for marking messages as read or unread for
     * the given message.
     *
     * @param  int  $messageId
     * @param  string  $method
     * @return \Illuminate\Http\JsonResponse
     */
    protected function handleMarkingAsRead(int $messageId, string $method): JsonResponse
    {
        $message = $this->getModel($messageId);

        // If you may view the message you may also mark it as read, because
        // when viewing the message you mark it as read.
        $this->authorize('view', $message);

        $message->$method(Auth::user());

        $message->load('user', 'users');

        return $this->createResourceResponse($message, 'message');
    }
}

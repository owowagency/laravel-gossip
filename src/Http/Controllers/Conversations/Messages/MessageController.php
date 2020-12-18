<?php

namespace OwowAgency\Gossip\Http\Controllers\Conversations\Messages;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use OwowAgency\Gossip\Managers\MessageManager;
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
            ->with([
                'media',
                'users' => function ($query) {
                    // This relation is used to determine who read the message. We
                    // only have to check that for the current authenticated user.
                    $query->where('users.id', Auth::id());
                },
            ])
            ->ofConversation($conversation)
            ->paginate()
            ->appends($request->query());

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

<?php

namespace OwowAgency\Gossip\Http\Controllers\Conversations;

use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Http\Controllers\Controller;

class ConversationController extends Controller
{
    /**
     * Returns models instances used for the index action.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', [Conversation::class]);

        $conversations = Conversation::with($this->relations(3))
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
     * @param  int  $conversationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        $this->authorize('view', $conversation);

        $conversation->load($this->relations());

        $resource = config('gossip.resources.conversation');

        return ok(new $resource($conversation));
    }

    /**
     * Get the relationship which should be eager loaded.
     *
     * @param  int  $messageCount
     * @return array
     */
    protected function relations(int $messageCount = 15): array
    {
        return [
            'users',
            'messages' => fn($query) => $query->with('user', 'users')
                ->latest()
                ->take($messageCount),
        ];
    }
}

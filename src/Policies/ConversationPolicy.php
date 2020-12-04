<?php

namespace OwowAgency\Gossip\Policies;

use OwowAgency\Gossip\Models\Conversation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy
{
    use HandlesAuthorization;

    /**
     * Check if the user has special privileges.
     *
     * @param \App\Models\User $user
     * @return bool|void
     */
    public function before($user)
    {
        // TODO Always return true for now until permissions are included.
        return true;
    }

    /**
     * Determine whether the user can index conversations.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny($user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the conversation.
     *
     * @param \App\Models\User $user
     * @param \OwowAgency\Gossip\Models\Conversation $conversation
     * @return bool
     */
    public function view($user, Conversation $conversation): bool
    {
        return $conversation->hasUser($user);
    }

    /**
     * Determine whether the user can create conversations.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create($user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update conversations.
     *
     * @param \App\Models\User $user
     * @param \OwowAgency\Gossip\Models\Conversation $conversation
     * @return bool
     */
    public function update($user, Conversation $conversation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the conversation.
     *
     * @param \App\Models\User $user
     * @param \OwowAgency\Gossip\Models\Conversation $conversation
     * @return bool
     */
    public function delete($user, Conversation $conversation): bool
    {
        return false;
    }
}
<?php

namespace OwowAgency\Gossip\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use OwowAgency\Gossip\Models\Contracts\HasConversationContract;

class ConversationPolicy
{
    use HandlesAuthorization;

    /**
     * Check if the user has special privileges.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @return bool|void
     */
    public function before(HasConversationContract $user)
    {
        if ($user->can('do all')) {
            return true;
        }
    }

    /**
     * Determine whether the user can index conversations.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @return bool
     */
    public function viewAny(HasConversationContract $user): bool
    {
        return $user->can('view any conversation');
    }

    /**
     * Determine whether the user can view the conversation.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @param  \OwowAgency\Gossip\Models\Conversation  $conversation
     * @return bool
     */
    public function view(HasConversationContract $user, $conversation): bool
    {
        return $conversation->hasUser($user);
    }

    /**
     * Determine whether the user can view conversations of an other user.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $target
     * @return bool
     */
    public function viewConversationsOf(HasConversationContract $user, $target): bool
    {
        return $user->is($target);
    }

    /**
     * Determine whether the user can create conversations.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @return bool
     */
    public function create(HasConversationContract $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update conversations.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @param  \OwowAgency\Gossip\Models\Conversation  $conversation
     * @return bool
     */
    public function update(HasConversationContract $user, $conversation): bool
    {
        // TODO let creator delete conversation.
        return false;
    }

    /**
     * Determine whether the user can delete the conversation.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @param  \OwowAgency\Gossip\Models\Conversation  $conversation
     * @return bool
     */
    public function delete(HasConversationContract $user, $conversation): bool
    {
        // TODO let creator delete conversation.
        return false;
    }
}

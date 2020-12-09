<?php

namespace OwowAgency\Gossip\Policies;

use OwowAgency\Gossip\Models\Message;
use Illuminate\Auth\Access\HandlesAuthorization;
use OwowAgency\Gossip\Models\Contracts\HasConversationContract;

class MessagePolicy
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
     * Determine whether the user can index messages.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @return bool
     */
    public function viewAny(HasConversationContract $user): bool
    {
        return $user->can('view any message');
    }

    /**
     * Determine whether the user can view the message.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @param  \OwowAgency\Gossip\Models\Message  $message
     * @return bool
     */
    public function view(HasConversationContract $user, Message $message): bool
    {
        return $message->conversation->hasUser($user);
    }

    /**
     * Determine whether the user can create messages.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @return bool
     */
    public function create(HasConversationContract $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update messages.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @param  \OwowAgency\Gossip\Models\Message  $message
     * @return bool
     */
    public function update(HasConversationContract $user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }

    /**
     * Determine whether the user can delete the message.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $user
     * @param  \OwowAgency\Gossip\Models\Message  $message
     * @return bool
     */
    public function delete(HasConversationContract $user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }
}

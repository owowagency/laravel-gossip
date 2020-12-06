<?php

namespace OwowAgency\Gossip\Policies;

use OwowAgency\Gossip\Models\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
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
        // TODO Fix permissions.
    }

    /**
     * Determine whether the user can index messages.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny($user): bool
    {
        // TODO Fix permissions.
        return true;
    }

    /**
     * Determine whether the user can view the message.
     *
     * @param \App\Models\User $user
     * @param \OwowAgency\Gossip\Models\Message $message
     * @return bool
     */
    public function view($user, Message $message): bool
    {
        return $message->conversation->hasUser($user);
    }

    /**
     * Determine whether the user can create messages.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create($user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update messages.
     *
     * @param \App\Models\User $user
     * @param \OwowAgency\Gossip\Models\Message $message
     * @return bool
     */
    public function update($user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }

    /**
     * Determine whether the user can delete the message.
     *
     * @param \App\Models\User $user
     * @param \OwowAgency\Gossip\Models\Message $message
     * @return bool
     */
    public function delete($user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }
}

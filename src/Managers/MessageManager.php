<?php

namespace OwowAgency\Gossip\Managers;

use Illuminate\Support\Facades\Auth;
use OwowAgency\Gossip\Support\Collection\MessageCollection;
use OwowAgency\Gossip\Models\Contracts\HasConversationContract;

class MessageManager
{
    /**
     * Mark all the messages in the given collection as read when the
     * $markAsRead variable is equal to true.
     *
     * @param  \OwowAgency\Gossip\Support\Collection\MessageCollection  $collection
     * @param  boolean  $markAsRead
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract|null  $user
     * @return \OwowAgency\Gossip\Support\Collection\MessageCollection
     */
    public static function handleMessageMarking(
        MessageCollection $collection,
        bool $markAsRead,
        HasConversationContract $user = null
    ): MessageCollection {
        if ($markAsRead) {
            $collection->markAsRead($user ?? Auth::user());
        }

        return $collection;
    }
}

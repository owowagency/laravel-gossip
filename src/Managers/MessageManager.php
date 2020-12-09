<?php

namespace OwowAgency\Gossip\Managers;

use Illuminate\Support\Facades\Auth;
use OwowAgency\Gossip\Support\Collection\MessageCollection;

class MessageManager
{
    /**
     * Create a new Eloquent Collection instance of messages.
     *
     * @param  array  $models
     * @return \OwowAgency\Gossip\Support\Collection\MessageCollection
     */
    public static function createCollection(array $models): MessageCollection
    {
        $collection = new MessageCollection($models);

        // If there is not authenticated user we can return the collection
        // immediately. This because we can't mark the messages as read.
        // Usually, this will only be the case while performing unit tests.
        if (Auth::guest()) {
            return $collection;
        }

        $markAsRead = filter_var(
            request()->get('mark_messages_as_read', false),
            FILTER_VALIDATE_BOOLEAN
        );

        if ($markAsRead) {
            $collection->markAsRead(Auth::user());
        }

        return $collection;
    }
}

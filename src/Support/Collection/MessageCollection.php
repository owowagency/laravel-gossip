<?php

namespace OwowAgency\Gossip\Support\Collection;

use Illuminate\Database\Eloquent\Collection;
use OwowAgency\Gossip\Models\Contracts\HasConversationContract;

class MessageCollection extends Collection
{
    /**
     * Mark messages as read if the force parameter is set to true.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $model
     * @param  bool  $force
     * @return \OwowAgency\Gossip\Support\Collection\MessageCollection
     */
    public function markAsRead(HasConversationContract $model, bool $force = false): MessageCollection
    {
        if ($force) {
            $this->updateReadAtStatus($model, true);
        }

        return $this;
    }

    /**
     * Force mark the messages as read.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $model
     * @return \OwowAgency\Gossip\Support\Collection\MessageCollection
     */
    public function forceMarkAsRead(HasConversationContract $model): MessageCollection
    {
        return $this->markAsRead($model, true);
    }

    /**
     * Mark all messages in this collection as unread.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $model
     * @return \OwowAgency\Gossip\Support\Collection\MessageCollection
     */
    public function markAsUnread(HasConversationContract $model): MessageCollection
    {
        $this->updateReadAtStatus($model, false);

        return $this;
    }

    /**
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $model
     * @param  bool  $markAsRead
     * @return void
     */
    protected function updateReadAtStatus(HasConversationContract $model, bool $markAsRead): void
    {
        $method = $markAsRead ? 'syncWithoutDetaching' : 'detach';

        $model->readMessages()->$method($this->pluck('id'));
    }
}

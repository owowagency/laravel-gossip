<?php

namespace OwowAgency\Gossip\Models\Concerns;

use OwowAgency\Gossip\Models\Conversation;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasConversations
{
    /**
     * The relationship to all the conversation which the user is participating
     * in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class);
    }
}

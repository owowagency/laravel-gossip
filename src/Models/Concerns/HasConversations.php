<?php

namespace OwowAgency\Gossip\Models\Concerns;

use OwowAgency\Gossip\Models\Message;
use OwowAgency\Gossip\Models\Conversation;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        return $this->belongsToMany(Conversation::class)
            ->withTimestamps();
    }

    /**
     * The relationship to all the messages send by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * The relationship to all the messages read by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function readMessages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class)
            ->withTimestamps();
    }
}

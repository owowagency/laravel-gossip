<?php

namespace OwowAgency\Gossip\Models\Concerns;

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
        return $this->belongsToMany(config('gossip.models.conversation'))
            ->withTimestamps();
    }

    /**
     * The relationship to all the messages send by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(config('gossip.models.message'));
    }

    /**
     * The relationship to all the messages read by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function readMessages(): BelongsToMany
    {
        return $this->belongsToMany(config('gossip.models.message'))
            ->withTimestamps();
    }
}

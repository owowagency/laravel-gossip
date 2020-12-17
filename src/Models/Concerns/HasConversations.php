<?php

namespace OwowAgency\Gossip\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

    /**
     * Scope a query to only include users of the given conversation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $conversation
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfConversation(Builder $query, Model $conversation): Builder
    {
        return $query->whereHas(
            'conversations',
            fn($query) => $query->where('conversations.id', $conversation->id),
        );
    }
}

<?php

namespace OwowAgency\Gossip\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface HasConversationContract
{
    /**
     * The relationship to all the conversation which the user is participating
     * in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conversations(): BelongsToMany;

    /**
     * The relationship to all the messages send by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany;

    /**
     * The relationship to all the messages read by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function readMessages(): BelongsToMany;
}

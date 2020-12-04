<?php

namespace OwowAgency\Gossip\Models;

use Illuminate\Database\Eloquent\Model;
use OwowAgency\AppliesHttpQuery\AppliesHttpQuery;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwowAgency\Gossip\Models\Concerns\HasDefaultRelations;
use OwowAgency\Gossip\Tests\Support\Database\Factories\ConversationFactory;

class Conversation extends Model
{
    use AppliesHttpQuery, HasDefaultRelations, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Http queryable rules.
     *
     * @var array
     */
    protected $httpQueryable = [
        'columns' => [
            'name',
            'created_at',
            'updated_at',
        ],
    ];
    
    /**
     * The relationship to all the messages of the conversation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * The relationship all the participants of the conversation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('gossip.user_model'));
    }

    /**
     * Scope a query to only include conversations of the given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser($query, $user)
    {
        return $query->whereHas(
            'users',
            fn($query) => $query->where('users.id', $user->id),
        );
    }

    /**
     * Determine if the given user is in the current model.
     *
     * @param  \App\Models\User|int|string  $user
     * @return bool
     */
    public function hasUser($user): bool
    {
        $userId = $user instanceof Model ? $user->id : $user;

        return $this->users()->where('users.id', $userId)->exists();
    }

    /**
     * Get the relationship which should be eager loaded.
     *
     * @param  int  $messageCount
     * @return array
     */
    public function getDefaultRelations(int $messageCount = 15): array
    {
        return [
            'users',
            'messages' => fn($query) => $query->with('user', 'users')
                ->latest()
                ->take($messageCount),
        ];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return ConversationFactory::new();
    }
}

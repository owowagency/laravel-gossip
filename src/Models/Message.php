<?php

namespace OwowAgency\Gossip\Models;

use Illuminate\Database\Eloquent\Model;
use OwowAgency\AppliesHttpQuery\AppliesHttpQuery;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwowAgency\Gossip\Tests\Support\Database\Factories\MessageFactory;

class Message extends Model
{
    use AppliesHttpQuery, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conversation_id', 'user_id', 'body',
    ];

    /**
     * Http queryable rules.
     *
     * @var array
     */
    protected $httpQueryable = [
        'columns' => [
            'created_at',
            'updated_at',
        ],
    ];
    
    /**
     * The relationship to the conversation which the message belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('gossip.user_model'));
    }

    /**
     * The relationship to the users to track who read the message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('gossip.user_model'))
            ->withTimestamps();
    }

    /**
     * The relationship to the conversation which the message belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Scope a query to only include messages of the given conversation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \OwowAgency\Gossip\Models\Conversation  $conversation
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfConversation($query, Conversation $conversation)
    {
        return $query->whereHas(
            'conversation',
            fn($query) => $query->where('conversations.id', $conversation->id),
        );
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return MessageFactory::new();
    }
}

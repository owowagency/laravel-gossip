<?php

namespace OwowAgency\Gossip\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use OwowAgency\AppliesHttpQuery\AppliesHttpQuery;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OwowAgency\Gossip\Support\Collection\MessageCollection;
use OwowAgency\Gossip\Models\Contracts\HasConversationContract;
use OwowAgency\Gossip\Support\Exceptions\RelationNotLoadedException;
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
     * Mark the notification as read.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $model
     * @return void
     */
    public function markAsRead(HasConversationContract $model)
    {
        $this->users()->syncWithoutDetaching($model);
    }

    /**
     * Mark the notification as unread.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $model
     * @return void
     */
    public function markAsUnread(HasConversationContract $model)
    {
        $this->users()->detach($model);
    }

    /**
     * Determine if a notification has been read.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $model
     * @return bool
     */
    public function read(HasConversationContract $model = null): bool
    {
        $this->checkIfUsersAreLoaded();

        $user = $this->users->first(function ($user) use ($model) {
            return ($model ?: Auth::user())->is($user);
        });

        return optional($user)->created_at !== null;
    }

    /**
     * Determine if a notification has not been read.
     *
     * @param  \OwowAgency\Gossip\Models\Contracts\HasConversationContract  $model
     * @return bool
     */
    public function unread(HasConversationContract $model = null)
    {
        return ! $this->read($model);
    }

    /**
     * Check if the users relationship is loaded. This is needed because
     * otherwise checking is a message is read might be to costly in time.
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function checkIfUsersAreLoaded(): void
    {
        throw_if(! $this->relationLoaded('users'), new RelationNotLoadedException('users'));
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

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
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

        return $collection->markAsRead(Auth::user(), $markAsRead);
    }
}

<?php

namespace OwowAgency\Gossip\Tests\Support\Models;

use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwowAgency\Gossip\Models\Concerns\HasConversations;
use OwowAgency\Gossip\Models\Contracts\HasConversationContract;
use OwowAgency\Gossip\Tests\Support\Database\Factories\UserFactory;

class User extends BaseUser implements HasConversationContract
{
    use HasConversations, HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}

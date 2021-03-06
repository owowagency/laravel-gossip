<?php

namespace OwowAgency\Gossip\Tests\Support\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as BaseUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwowAgency\Gossip\Models\Concerns\HasConversations;
use OwowAgency\Gossip\Models\Contracts\HasConversationContract;
use OwowAgency\Gossip\Tests\Support\Database\Factories\UserFactory;

class User extends BaseUser implements HasConversationContract
{
    use HasConversations, HasFactory, HasRoles;

    /**
     * The guard name used by the permission package of Spatie.
     *
     * @var string
     */
    protected $guard_name = 'web';

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

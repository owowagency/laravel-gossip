<?php

return [

    'models' => [
        'user' => \App\Models\User::class,
        'conversation' => \OwowAgency\Gossip\Models\Conversation::class,
        'message' => \OwowAgency\Gossip\Models\Message::class,
    ],

    'resources' => [
        'user' => \App\Http\Resources\UserResource::class,
        'conversation' => \OwowAgency\Gossip\Http\Resources\ConversationResource::class,
        'message' => \OwowAgency\Gossip\Http\Resources\MessageResource::class,
        'media' => \Owowagency\LaravelMedia\Resources\MediaResource::class,
    ],

];

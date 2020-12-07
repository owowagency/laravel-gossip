<?php

return [

    'user_model' => \App\Models\User::class,

    'resources' => [

        'conversation' => \OwowAgency\Gossip\Http\Resources\ConversationResource::class,
        'message' => \OwowAgency\Gossip\Http\Resources\MessageResource::class,

    ],

];

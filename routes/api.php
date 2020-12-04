<?php

use Illuminate\Support\Facades\Route;
use OwowAgency\Gossip\Http\Controllers\Conversations\ConversationController;
use OwowAgency\Gossip\Http\Controllers\Conversations\Messages\MessageController;
use OwowAgency\Gossip\Http\Controllers\Users\Conversations\ConversationController as UserConversationController;

Route::middleware('auth')
    ->apiResource('conversations', ConversationController::class, [
        'only' => ['index', 'show'],
    ]);

Route::prefix('conversations/{conversation}')
    ->middleware('auth')
    ->group(function () {
        Route::get('messages', MessageController::class);
    });

Route::get('/users/{user}/conversations', [UserConversationController::class, 'index'])
    ->middleware('auth');

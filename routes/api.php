<?php

use Illuminate\Support\Facades\Route;
use OwowAgency\Gossip\Http\Controllers\Conversations\ConversationController;
use OwowAgency\Gossip\Http\Controllers\Conversations\Messages\MessageController;

Route::middleware('auth')
    ->apiResource('conversations', ConversationController::class, [
        'only' => ['index', 'show'],
    ]);

Route::prefix('conversations/{conversation}')
    ->middleware('auth')
    ->group(function () {
        Route::get('messages', MessageController::class);
    });

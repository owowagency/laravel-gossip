<?php

use Illuminate\Support\Facades\Route;
use OwowAgency\Gossip\Http\Controllers\Messages\MarkAsReadController;
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

Route::prefix('users/{user}')
    ->middleware('auth')
    ->group(function () {
        Route::get('conversations', [UserConversationController::class, 'index']);
    });

Route::prefix('messages/{message}')
    ->middleware('auth')
    ->group(function () {
        Route::post('read', [MarkAsReadController::class, 'store']);
        Route::delete('unread', [MarkAsReadController::class, 'destroy']);
    });

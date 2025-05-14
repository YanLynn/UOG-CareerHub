<?php

use GuzzleHttp\Psr7\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::info('Channel auth user:', [$user]); // 🔍 See if JWT auth works
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('chat.{chatRoomId}', function ($user, $chatRoomId) {
//     return true; // Or implement authorization logic
// });

Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    Log::info('Channel auth user:', [$user]); // 🔍 See if JWT auth works
    return true;
});


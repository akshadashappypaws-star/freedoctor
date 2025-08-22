<?php

use Illuminate\Support\Facades\Broadcast;

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
    return (int) $user->id === (int) $id;
});

// User notification channel - For user portal notifications
Broadcast::channel('user.{id}', function ($user, $id) {
    return auth('user')->check() && auth('user')->id() == (int) $id;
});

// Admin Dashboard Channel - Only authenticated admins can access
Broadcast::channel('admin-dashboard', function ($user) {
    // Check if the user is an authenticated admin
    return auth()->guard('admin')->check();
});
Broadcast::channel('doctor.{id}', function ($user, $id) {
    return auth('doctor')->check() && auth('doctor')->id() == (int) $id;
});

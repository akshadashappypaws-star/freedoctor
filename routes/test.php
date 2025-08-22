<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Test\NotificationTestController;

// Test routes for notification system
Route::get('/test/notification/user/{userId}', [NotificationTestController::class, 'testUserNotification'])->name('test.notification.user');
Route::get('/test/notification/doctor/{doctorId}', [NotificationTestController::class, 'testDoctorNotification'])->name('test.notification.doctor');
Route::get('/test/notification/admin/{adminId}', [NotificationTestController::class, 'testAdminNotification'])->name('test.notification.admin');
Route::get('/test/notification/all', [NotificationTestController::class, 'testAllNotifications'])->name('test.notification.all');

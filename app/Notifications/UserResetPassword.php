<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordReset;

class UserResetPassword extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('user.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]));

        // Use our custom PasswordReset mail class
        Mail::to($notifiable->email)->send(new PasswordReset($notifiable, $resetUrl));
        
        // Return null since we're handling the mail sending manually
        return null;
    }
}

<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Setting\Setting;

class ResetPasswordNotification extends Notification
{
    public function __construct(protected string $token) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $setting = Setting::first();
        $resetLink = url("/user/reset-password?token=".$this->token."&email=".$notifiable->email);
        $logoUrl = url('/storage/upload_files/settings/'.$setting->logo);

        return (new MailMessage)
            ->subject($setting->app_name. ' : Reset Kata Sandi')
            ->view('notification.reset_password', [
                'resetLink' => $resetLink,
                'logoUrl' => $logoUrl,
            ]);
    }
}

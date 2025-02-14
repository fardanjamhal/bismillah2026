<?php

namespace App\Services;
use App\Models\Setting\Setting;
use Config;

class MailService
{
    /**
     * Upload file
     *
     * @param UploadedFile $file
     * @param string $path|null
     * @param string $disk
     * @return boolean|string
     */

    public function loadMailConfig()
    {
        $setting = Setting::first();

        if ($setting && $setting->notification_type == 2) {
            Config::set('app.name', $setting->app_name);
            Config::set('mail.mailers.smtp.host', $setting->mail_host);
            Config::set('mail.mailers.smtp.port', $setting->mail_port);
            Config::set('mail.mailers.smtp.username', $setting->mail_username);
            Config::set('mail.mailers.smtp.password', $setting->mail_password);
            Config::set('mail.mailers.smtp.encryption', $setting->mail_encryption);
            Config::set('mail.from.address', $setting->mail_from_address);
            Config::set('mail.from.name', $setting->mail_from_name);
        }
    }
}

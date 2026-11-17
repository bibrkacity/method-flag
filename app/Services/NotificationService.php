<?php

namespace App\Services;

namespace App\Services;

use App\Attributes\CheckFeatureFlag;

class NotificationService
{
    #[CheckFeatureFlag(flagName: 'AB', fallbackReturn: 'Was not executed')]
    public function sendSmsNotification(string $message): string
    {
        return 'SMS відправлено: '.$message;
    }

    public function sendEmailNotification(string $message): string
    {
        return 'Email відправлено: '.$message;
    }
}

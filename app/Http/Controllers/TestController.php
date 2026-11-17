<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Response;

class TestController extends Controller
{
    public function __construct(private NotificationService $notificationService) {}

    public function test(): Response
    {
        $sms = $this->notificationService->sendSmsNotification('Hello, world!');
        $email = $this->notificationService->sendEmailNotification('Hello, world!');

        return response()->view('test', compact('sms', 'email'));
    }
}

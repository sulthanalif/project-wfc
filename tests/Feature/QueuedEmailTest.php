<?php

namespace Tests\Feature;

use App\Mail\NotificationAccOrder;
use App\Mail\NotificationApproveUser;
use App\Mail\NotificationDistribution;
use App\Mail\NotificationPayment;
use App\Models\Distribution;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\QueuedResetPassword;
use App\Notifications\QueuedVerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class QueuedEmailTest extends TestCase
{
    // Semua email harus lewat antrean supaya request tidak menunggu SMTP dan jeda worker berlaku.
    public function test_all_emails_are_queued(): void
    {
        Mail::fake();
        Mail::to('a@example.com')->send(new NotificationAccOrder(new Order));
        Mail::to('a@example.com')->send(new NotificationApproveUser);
        Mail::to('a@example.com')->send(new NotificationDistribution(new Distribution));
        Mail::to('a@example.com')->send(new NotificationPayment(new Payment));
        Mail::assertNothingSent();
        Mail::assertQueuedCount(4);

        Notification::fake();
        $user = new User(['email' => 'a@example.com']);
        $user->sendEmailVerificationNotification();
        $user->sendPasswordResetNotification('token');
        Notification::assertSentTo($user, QueuedVerifyEmail::class);
        Notification::assertSentTo($user, QueuedResetPassword::class);
        $this->assertInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class, new QueuedVerifyEmail);
        $this->assertInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class, new QueuedResetPassword('t'));
    }
}

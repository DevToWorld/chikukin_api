<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $resetUrl) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('【管理者】パスワード再設定のご案内')
            ->line('管理者パスワード再設定のリクエストを受け付けました。')
            ->action('パスワードを再設定する', $this->resetUrl)
            ->line('このリンクの有効期限は約60分です。')
            ->line('もし本メールに心当たりがない場合は、このメールを無視してください。');
    }
}


<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;
    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $urlToResetForm = "http://localhost:8000/user/reset-password?token=". $this->token;
        return (new MailMessage)
                    ->subject('تغییر رمز عبور ')
                    ->line('سلام، ما این ایمیل را بخاطر درخواست شما ارسال کردیم ')
                    ->line(' این پیام مخصوص تغییر رمز عبور در سایت mehravidniroo میباشد که در 60 ثانیه اینده منقضی میشود.')
                    ->action('تغییر رمز عبور', $urlToResetForm)
                    ->line('از اعتماد شما متشکریم!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

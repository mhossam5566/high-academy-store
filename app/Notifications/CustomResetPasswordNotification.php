<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
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
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('إعادة تعيين كلمة المرور - High Academy Store')
            ->greeting('مرحباً ' . $notifiable->name . '!')
            ->line('لقد تلقيت هذا البريد الإلكتروني لأنه تم طلب إعادة تعيين كلمة المرور لحسابك.')
            ->line('انقر على الزر أدناه لإعادة تعيين كلمة المرور:')
            ->action('إعادة تعيين كلمة المرور', $resetUrl)
            ->line('رابط إعادة تعيين كلمة المرور سينتهي خلال ' . config('auth.passwords.users.expire') . ' دقيقة.')
            ->line('إذا لم تطلب إعادة تعيين كلمة المرور، فلا حاجة لاتخاذ أي إجراء.')
            ->salutation('مع أطيب التحيات،' . "\n" . 'فريق High Academy Store');
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

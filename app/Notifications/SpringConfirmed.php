<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SpringConfirmed extends Notification
{
    use Queueable;

    private $spring;

    /**
     * Create a new notification instance.
     *
     * @param $spring
     */
    public function __construct($spring)
    {
        $this->spring = $spring;
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
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/springs/'.$this->spring->code);

        return (new MailMessage)
                    ->subject(__('springs.spring_confirmed'))
                    ->markdown('mail.spring-confirmed', ['url' => $url, 'name' => $this->spring->name]);
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

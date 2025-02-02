<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotification extends Notification
{
    use Queueable;

    private $sensor;
    private $temperature;
    private $humidity;
    private $tempThreshold;
    private $humidThreshold;
    private $notificationTime;

    /**
     * Create a new notification instance.
     */
    public function __construct($sensor, $temperature, $humidity, $tempThreshold, $humidThreshold, $notificationTime)
    {
        $this->sensor = $sensor;
        $this->temperature = $temperature;
        $this->humidity = $humidity;
        $this->tempThreshold = $tempThreshold;
        $this->humidThreshold = $humidThreshold;
        $this->notificationTime = $notificationTime;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toTelegram(object $notifiable)
    {
        try {
            return TelegramMessage::create()->to('-4637386839')
                ->content("*🚨 Lab Threshold Alert*")
                ->line("\n\n*Location:* {$this->sensor->lab_room_name}")
                ->line("\n*Current Readings:*")
                ->line("• Temperature: *" . number_format($this->temperature, 2) . "* °C")
                ->line("• Humidity: *" . number_format($this->humidity, 2) . "* %")
                ->line("\n*Threshold Values:*")
                ->line("• Temperature: *" . number_format($this->tempThreshold, 2) . "* °C")
                ->line("• Humidity: *" . number_format($this->humidThreshold, 2) . "* %")
                ->line("\n*Detected at:* " . $this->notificationTime->format('d/m/Y h:i:s A'));

        } catch (\Exception $ex) {
            \Log::error($ex->getMessage());
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

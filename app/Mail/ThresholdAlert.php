<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ThresholdAlert extends Mailable
{
    use Queueable, SerializesModels;

    private $sensor;
    private $temperature;
    private $humidity;
    private $tempThreshold;
    private $humidThreshold;

    /**
     * Create a new message instance.
     */
    public function __construct($sensor, $temperature, $humidity, $tempThreshold, $humidThreshold)
    {
        $this->sensor = $sensor;
        $this->temperature = $temperature;
        $this->humidity = $humidity;
        $this->tempThreshold = $tempThreshold;
        $this->humidThreshold = $humidThreshold;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lab Threshold Alert',
        );
    }


    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.threshold_alert',
            with: [
                'sensor' => $this->sensor,
                'temperature' => $this->temperature,
                'humidity' => $this->humidity,
                'tempThreshold' => $this->tempThreshold,
                'humidThreshold' => $this->humidThreshold,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

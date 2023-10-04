<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeatherEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $weatherData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($weatherData)
    {
        $this->weatherData = $weatherData;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Weather Email',
        );
    }

    public function build()
    {
        return $this->view('email.sendMessage')->with(['weatherData' => $this->weatherData]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}

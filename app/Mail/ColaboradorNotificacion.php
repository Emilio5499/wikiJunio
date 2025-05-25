<?php

namespace App\Mail;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ColaboradorNotificacion extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public Article $article;
    public function __construct(Article $article)
     {
         $this->article = $article;
     }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Colaborador Notificacion',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->subject('Eres un colaborador en un post')
            ->view('emails.colaborador-notificacion');
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

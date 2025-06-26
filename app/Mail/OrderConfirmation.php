<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/*
1. Queueable
Deze trait maakt het mogelijk dat de class in een queue gezet kan worden om later verwerkt te worden.
Hiermee kan je bijvoorbeeld een e-mail of job asynchroon laten uitvoeren, zodat je app niet hoeft te wachten op de verwerking.
Zorgt ervoor dat eigenschappen zoals verbinding (connection), queue naam (queue), delay (delay) en meer makkelijk ingesteld kunnen worden.
Simpel gezegd: het maakt je class klaar om via de Laravel queue system te draaien.

2. SerializesModels
Zorgt ervoor dat Eloquent modellen netjes geserializeerd en gedeserializeerd worden als je ze in je job, mail of event gebruikt.
In plaats van het hele model mee te nemen in de queue (wat problemen kan geven), wordt alleen de primaire sleutel van het model opgeslagen.
Bij het uitvoeren van de job wordt het model dan opnieuw geladen uit de database.
Dit voorkomt problemen zoals het opslaan van verouderde modeldata in de queue, en zorgt voor een betrouwbare herlaad-behandeling.
*/

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.confirmation',
            with: [
                'order' => $this->order,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $orderCorrectRelations = $this->order->load(['customer', 'payments', 'items', 'addresses', 'shipments']);

        $pdf = Pdf::loadView('exports.orders.email-invoice', ['order' => $orderCorrectRelations]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'invoice-' . $orderCorrectRelations->order_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Campaign;
use App\Models\PatientRegistration;
use Illuminate\Mail\Mailables\Attachment;

class CampaignRegistrationConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $campaign;
    public $registration;
    public $invoicePdf;

    /**
     * Create a new message instance.
     *
     * @param Campaign $campaign
     * @param PatientRegistration $registration
     * @param string|null $invoicePdf Path to PDF file
     */
    public function __construct(Campaign $campaign, PatientRegistration $registration, string $invoicePdf = null)
    {
        $this->campaign = $campaign;
        $this->registration = $registration;
        $this->invoicePdf = $invoicePdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Campaign Registration Confirmed - ' . $this->campaign->title;
        
        $mail = $this->from(config('mail.from.address'), config('mail.from.name'))
                     ->subject($subject)
                     ->view('emails.campaign-registration-confirmation')
                     ->with([
                         'campaign' => $this->campaign,
                         'registration' => $this->registration,
                     ]);

        // Attach PDF invoice if provided
        if ($this->invoicePdf && file_exists($this->invoicePdf)) {
            $registrationNumber = $this->registration->registration_number ?? 'REG' . str_pad($this->registration->id, 6, '0', STR_PAD_LEFT);
            $mail->attach($this->invoicePdf, [
                'as' => 'Invoice_' . $registrationNumber . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        $attachments = [];

        if ($this->invoicePdf && file_exists($this->invoicePdf)) {
            $registrationNumber = $this->registration->registration_number ?? 'REG' . str_pad($this->registration->id, 6, '0', STR_PAD_LEFT);
            
            $attachments[] = Attachment::fromPath($this->invoicePdf)
                                     ->as('Invoice_' . $registrationNumber . '.pdf')
                                     ->withMime('application/pdf');
        }

        return $attachments;
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationsIssuedVendorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.notifications.issuedvendor')
            ->with([
                'name' => $this->data['name'],
                'url' => $this->data['url'],
            ])->subject('Issued')
            ->cc($this->data['cc'])
            ->to($this->data['to']);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationsDisapprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->markdown('mails.notifications.disapproved')
            ->with([
                'name' => $this->data['name'],
                'url' => $this->data['url'],
                'project' => $this->data['project'],
                'company' => $this->data['company'],
                'no' => $this->data['no'],
                'purpose' => $this->data['purpose'],
                'amount' => $this->data['amount'],
                'approver' => $this->data['approver'],
                'remarks' => $this->data['remarks'],
            ])->subject('Transaction Disapproved')
            ->to($this->data['to']);
    }
}

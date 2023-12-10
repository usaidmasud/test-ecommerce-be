<?php

namespace App\Jobs;

use App\Mail\OrderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailBuyerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $send_mail;
    protected $data;
    /**
     * Create a new job instance.
     */
    public function __construct($send_mail, $data)
    {
        $this->send_mail = $send_mail;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // $email = new OrderMail();
        Mail::to($this->send_mail)->queue(new OrderMail($this->data));
    }
}

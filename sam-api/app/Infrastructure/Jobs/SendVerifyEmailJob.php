<?php

namespace App\Infrastructure\Jobs;

use App\Infrastructure\Mail\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class SendVerifyEmailJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $data;

    public function __construct($email, $data)
    {
        $this->email = $email;
        $this->data = $data;
    }

    public function handle(): void
    {
        Mail::to($this->email)->send(new VerifyEmail($this->data));
    }
}

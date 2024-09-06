<?php

namespace App\Jobs;

use App\Mail\MailSendTokenPreRegister;
use App\Models\MailToken;
use App\Models\PreRegister;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailSenderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 25;
    public int $maxExceptions = 3;
   private PreRegister $register;

   private string $token;

    public function __construct(string $preRegister,string $token)
    {
        $this->register = PreRegister::find($preRegister);
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->register->email)->send(new MailSendTokenPreRegister($this->register->id, $this->token));
    }
}

<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCreatedEmail;

class SendAccountCreatedEmailJob implements ShouldQueue
{
    use Queueable;

    public $user;
    public $password;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user)->send(new AccountCreatedEmail($this->user, $this->password));
    }
}

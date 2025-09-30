<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerifyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    /**
     * Number of times to retry the job if it fails
     */
    public $tries = 3;

    /**
     * Maximum time (seconds) the job should run before timing out
     * Low timeout to prevent memory buildup
     */
    public $timeout = 30;

    /**
     * Number of seconds to wait before retrying
     */
    public $backoff = 30;

    /**
     * Maximum number of exceptions to allow before failing
     */
    public $maxExceptions = 2;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->notify(new VerifyEmail);
    }

    /**
     * Handle job failure gracefully without consuming resources
     */
    public function failed(\Throwable $exception)
    {
        // Log the error for debugging (lightweight)
        Log::error("Email verification failed for user {$this->user->email}", [
            'error' => $exception->getMessage(),
            'user_id' => $this->user->id
        ]);
    }
}

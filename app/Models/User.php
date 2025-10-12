<?php

namespace App\Models;

use App\Jobs\VerifyEmailJob;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use MustVerifyEmailTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'EmailId', 'email');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'EmailId', 'email');
    }

    public function sendEmailVerificationNotification()
    {
        // Send verification email after the HTTP response is returned to the client,
        // so the request doesn't block or hit Nginx/PHP-FPM timeouts. This does NOT
        // require a queue worker and runs in the same PHP process after response.
        VerifyEmailJob::dispatchAfterResponse($this);
    }
}

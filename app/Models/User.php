<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \\Illuminate\\Support\\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \\Illuminate\\Support\\Carbon|null $created_at
 * @property \\Illuminate\\Support\\Carbon|null $updated_at
 * @property bool $is_approved
 * @property string $role
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use MustVerifyEmailTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_approved',
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
            'is_approved' => 'boolean',
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

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}

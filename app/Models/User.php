<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\EmailVerificationNotification;
use App\Services\EmailVerificationService;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user',
        'name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    /**
     * Enviar notificación de verificación de email
     */
    public function sendEmailVerificationNotification(): void
    {
        $service = app(EmailVerificationService::class);
        $token = $service->generateToken($this->email, $this->id);
        $this->notify(new EmailVerificationNotification($token));
    }

    /**
     * Verificar el email del usuario
     */
    public function markEmailAsVerified(): void
    {
        $this->email_verified_at = now();
        $this->save();

        $service = app(EmailVerificationService::class);
        $service->deleteToken($this->email);
    }
}

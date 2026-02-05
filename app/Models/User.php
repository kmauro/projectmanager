<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\EmailVerificationNotification;

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
        'access_level_id',
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
     * Relación con nivel de acceso
     */
    public function accessLevel()
    {
        return $this->belongsTo(AccessLevel::class);
    }

    /**
     * Generar y guardar token de verificación de email
     */
    public function generateEmailVerificationToken()
    {
        $token = Str::random(64);
        
        DB::table('email_verification_tokens')->updateOrInsert(
            ['email' => $this->email],
            [
                'token' => hash('sha256', $token),
                'user_id' => $this->id,
                'created_at' => now(),
            ]
        );
        
        return $token;
    }

    /**
     * Enviar notificación de verificación de email
     */
    public function sendEmailVerificationNotification()
    {
        $token = $this->generateEmailVerificationToken();
        $this->notify(new EmailVerificationNotification($token));
    }

    /**
     * Verificar el email del usuario
     */
    public function markEmailAsVerified()
    {
        $this->email_verified_at = now();
        $this->save();
        
        // Eliminar el token de verificación
        DB::table('email_verification_tokens')->where('email', $this->email)->delete();
    }
}

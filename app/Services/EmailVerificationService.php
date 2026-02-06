<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmailVerificationService
{
    /**
     * Generar y guardar token de verificación de email
     */
    public function generateToken(string $email, int $userId): string
    {
        $token = Str::random(64);

        DB::table('email_verification_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => hash('sha256', $token),
                'user_id' => $userId,
                'created_at' => now(),
            ]
        );

        return $token;
    }

    /**
     * Eliminar token de verificación de email
     */
    public function deleteToken(string $email): void
    {
        DB::table('email_verification_tokens')->where('email', $email)->delete();
    }
}

<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public static function insertUser($user)
    {
        return User::create($user);
        
    }

    public function getUserById($userId)
    {
        return User::find($userId);
    }

    public function getUserByUsername($username)
    {
        return User::where('user', $username)->first();
    }

    public function updateUserPassword($user, $credentials)
    {
        $user->update($credentials);
    }

    public static function insertToken($email, $token, $userId)
    {
        DB::table('email_verification_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => hash('sha256', $token),
                'user_id' => $userId,
                'created_at' => now(),
                ]
            );
        
    }

    public static function deleteToken($email)
    {
        DB::table('email_verification_tokens')->where('email', $email)->delete();
    }

    public function existsToken($hashedToken)
    {
        return DB::table('email_verification_tokens')
            ->where('token', $hashedToken)
            ->first();
    }
}

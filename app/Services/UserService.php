<?php 

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{

    use Notifiable;

    public function createUser($user)
    {
        return  UserRepository::insertUser($user);
    }

    public function sendEmailVerificationNotification($user)
    {
        $token = $this->generateToken($user->email, $user->id);
        $user->sendNotification($token);
    }

    public function generateToken($email, $userId)
    {
        $token = Str::random(64);

        UserRepository::insertToken($email, $token, $userId);

        return $token;
    }

    public function deleteToken($email)
    {
        UserRepository::deleteToken($email);
    }

    public function verifyEmailToken($token)
    {
        $userRepo = app(UserRepository::class);

        $hashedToken = hash('sha256', $token);
        $verification = $userRepo->existsToken($hashedToken);

        if (!$verification) {
            return $returnInfo = [
                'view' => 'user/create',
                'state' => 'error',
                'message' => 'El enlace de verificación no es válido o ha expirado.'
            ];
        }

        $user = $userRepo->getUserById($verification->user_id);
        
        if (!$user) {
            return $returnInfo = [
                'view' => 'user/create',
                'state' => 'error',
                'message' => 'Usuario no encontrado.'
            ];
        }
        
        if (!$user->email_verified_at == null) {
            return $returnInfo = [
                'view' => 'user/create',
                'state' => 'info',
                'message' => 'Tu correo electrónico ya ha sido verificado anteriormente.'
            ];
            
        }
        
        // Verificar el email
        $this->markEmailAsVerified($user);
        return $returnInfo = [
            'view' => 'user/create',
            'state' => 'success',
            'message' => '¡Correo electrónico verificado exitosamente!'
        ];
    }

    public function markEmailAsVerified($user)
    {
        $user->email_verified_at = now();
        $user->save();
        $this->deleteToken($user->email);
    }

    public static function login($credentials, $request)
    {
        if (Auth::attempt(['user' => $credentials['user'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return true;
        }
        return false;
    }

    public static function changePassword($credentials)
    {
        $userRepo = app(UserRepository::class);
        $credentials['password'] = Hash::make($credentials['password']);
        $user = $userRepo->getUserByUsername($credentials['user']);
       if($user){ 
            $userRepo->updateUserPassword($user, $credentials);
            return true;
       } 
       return false;
    }

    
}
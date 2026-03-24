<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //GET Methods
    public function getChangePassword()
    {
        //$this->authorize('update', User::class);
        return view('user.edit');
    }

    public function getLogout(Request $request)
    {
        return view('auth.logout');
    }

    public function getAdd()
    {
        //$this->authorize('create', User::class);
        return view('user.create');
    }

    public function verifyEmail($token)
    {
        $hashedToken = hash('sha256', $token);
        $verification = DB::table('email_verification_tokens')
            ->where('token', $hashedToken)
            ->first();
        if (!$verification) {
            return redirect('user/create')->with('error', 'El enlace de verificación no es válido o ha expirado.');
        }
        $user = User::find($verification->user_id);
        if (!$user) {
            return redirect('user/create')->with('error', 'Usuario no encontrado.');
        }
        if ($user->email_verified_at) {
            return redirect('user/create')->with('info', 'Tu correo electrónico ya ha sido verificado anteriormente.');
        }
        // Verificar el email
        $user->markEmailAsVerified();
        return redirect('user/create')->with('success', '¡Correo electrónico verificado exitosamente!');
    }
    
    //POST Methods
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user' => 'required|string',
            'password' => 'required|string',
        ], [
            'user.required' => 'El usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);
        if (Auth::attempt(['user' => $credentials['user'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            $user = Auth::user();
            return redirect()->intended(route('dashboard'));
        }
        return back()->withErrors([
            'user' => 'Las credenciales ingresadas no son correctas.',
        ])->onlyInput('user');
    }

    public function createUser(Request $request)
    {
        $this->authorize('create', User::class);
        $data = $request->validate([
            'user' => 'required|string|max:255|unique:users,user',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:15',
            'access_level_id' => 'required|exists:access_levels,id',
        ], [
            'user.required' => 'El nombre de usuario es obligatorio.',
            'user.unique' => 'Este nombre de usuario ya está en uso.',
            'name.required' => 'El nombre completo es obligatorio.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'access_level_id.required' => 'Debe seleccionar un nivel de acceso.',
            'access_level_id.exists' => 'El nivel de acceso seleccionado no es válido.',
        ]);
        $user = User::create($data);
        // Enviar email de verificación
        try {
            $user->sendEmailVerificationNotification();
            return redirect('user/create')->with('success', 'Usuario registrado exitosamente. Por favor, verifica tu correo electrónico.');
        } catch (\Exception $e) {
            // Si falla el envío del email, aún así guardamos el usuario
            return redirect('user/create')->with('success', 'Usuario registrado exitosamente. Error al enviar email de verificación, contacta al administrador.');
        }
    }

    public function changePassword(Request $request)
    {
        $data = request()->validate([
            'user' => 'required|string|max:255',
            'password' => 'required|string|max:255'
        ]);
        
        $data['password'] = Hash::make($data['password']);
        $db_user = User::where('user', $data['user'])->first();
       if($db_user){
            $this->authorize('update', $db_user);    
            $db_user->update($data);
            return redirect('dashboard')->with('success', 'Contraseña cambiada exitosamente.');
       } 
       return redirect('dashboard')->with('error', 'No se encontró el usuario especificado.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

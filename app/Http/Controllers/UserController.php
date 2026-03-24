<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;


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
        $userService = app(UserService::class);
        $verification = $userService->verifyEmailToken($token);
        return redirect($verification['view'])->with($verification['state'], $verification['message']);
        
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
        $response = UserService::login($credentials, $request);
        if($response){
            return redirect()->intended(route('dashboard'));
        }else{
            return back()->withErrors([
                'user' => 'Las credenciales ingresadas no son correctas.',
            ])->onlyInput('user');
        }
    }

    public function createUser(Request $request)
    {
        $data = $request->validate([
            'user' => 'required|string|max:255|unique:users,user',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:15',
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
        ]);
        $userService = app(UserService::class);
        $user = $userService->createUser($data);
        // Enviar email de verificación

        try {
            $userService->sendEmailVerificationNotification($user);
            return redirect('user/create')->with('success', 'Usuario registrado exitosamente. Por favor, verifica tu correo electrónico.');
        } catch (\Exception $e) {
            // Si falla el envío del email, aún así guardamos el usuario
            return redirect('user/create')->with('success', 'Usuario registrado exitosamente. Error al enviar email de verificación, contacta al administrador.');   
        }
        
        
    }

    public function changePassword(Request $request)
    {
        $credentials = request()->validate([
            'user' => 'required|string|max:255',
            'password' => 'required|string|min:8'
        ],[
            'user.required' => 'El nombre de usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);
        if(UserService::changePassword($credentials)){
            return back()->with('success', 'Contraseña cambiada exitosamente.');
        }else{
            return back()->with('error', 'No se encontró el usuario especificado.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

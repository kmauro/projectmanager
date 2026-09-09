<?php

namespace App\Domains\Users\Controllers;

use App\Domains\Users\Models\User;
use App\Domains\Users\Requests\LoginRequest;
use App\Domains\Users\Requests\UpdatePasswordRequest;
use App\Domains\Users\Requests\UpdateProfileRequest;
use App\Domains\Users\Requests\UserRequest;
use App\Domains\Users\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UserController extends Controller
{
    public function showSignup(): InertiaResponse
    {
        return Inertia::render('Auth/Signup', [
            'title' => 'Registrarse',
        ]);
    }

    public function showLogin(): InertiaResponse
    {
        return Inertia::render('Auth/Login', [
            'title' => 'Iniciar sesión',
        ]);
    }

    public function showUsers(UserService $userService): InertiaResponse
    {
        abort_unless((int) Auth::user()?->role_id === 1, 403);

        return Inertia::render('User/Index', [
            'title' => 'Usuarios',
            'users' => $userService->getAllUsers(),
        ]);
    }

    public function showUser(User $user, UserService $userService): InertiaResponse
    {
        $isCurrentUser = Auth::id() === $user->id;
        $isAdmin = (int) Auth::user()?->role_id === 1;

        abort_unless($isCurrentUser || $isAdmin, 403);

        return $this->renderProfile(
            "Perfil de {$user->name} {$user->surname}",
            $userService->getUserById($user->id),
            $isCurrentUser,
        );
    }

    public function showProfile(UserService $userService): InertiaResponse
    {
        $user = $userService->getAuthenticatedUser();

        abort_unless($user !== null, 403);

        return $this->renderProfile('Mi perfil', $user, true);
    }

    public function updateProfile(UpdateProfileRequest $request, UserService $userService): RedirectResponse
    {
        $user = $userService->getAuthenticatedUser();

        abort_unless($user !== null, 403);

        $userService->updateProfile($user->id, $request->validated());

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(UpdatePasswordRequest $request, UserService $userService): RedirectResponse
    {
        $data = $request->validatedPasswordData();
        $user = $userService->getAuthenticatedUser();

        abort_unless($user !== null, 403);

        if (! $userService->updatePassword($user->id, $data['current_password'], $data['new_password'])) {
            throw ValidationException::withMessages([
                'current_password' => 'La contraseña actual es incorrecta.',
            ]);
        }

        return to_route('profile')->with('success', 'Contraseña actualizada correctamente.');
    }

    public function login(LoginRequest $request, UserService $userService): RedirectResponse
    {
        $credentials = $request->validatedLoginData();

        if (! $userService->authenticate($credentials['username'], $credentials['password'], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function signup(UserRequest $request, UserService $userService): RedirectResponse
    {
        $user = $userService->register($request->validatedUserData());

        Auth::login($user);
        $request->session()->regenerate();

        return to_route('home');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }

    private function renderProfile(string $title, User $user, bool $isCurrentUser): InertiaResponse
    {
        return Inertia::render('User/Profile', [
            'title' => $title,
            'user' => $user->loadMissing('role'),
            'isCurrentUser' => $isCurrentUser,
        ]);
    }
}

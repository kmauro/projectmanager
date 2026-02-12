<x-layout>
    <div class="row">
        <h1 class="col-3">Iniciar sesión</h1>
    </div>

    @if(session('success'))
        <div class="row">
            <div class="col-1"></div>
            <div class="col-4">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="row">
            <div class="col-1"></div>
            <div class="col-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="row">
            <div class="col-1"></div>
            <div class="col-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="row mt-3">
        <div class="col-1"></div>
        <div class="col-4">
            <form class="row g-3" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="col-12">
                    <label for="user" class="form-label">Usuario <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        class="form-control @error('user') is-invalid @enderror"
                        id="user"
                        name="user"
                        autocomplete="off"
                        placeholder="Nombre de usuario"
                        value="{{ old('user') }}"
                        required
                    >
                    @error('user')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                    <input
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        autocomplete="off"
                        placeholder="Tu contraseña"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>

            <div class="mt-3">
                <span>¿No tienes cuenta?</span>
                <a href="{{ url('/user/create') }}">Regístrate aquí</a>
            </div>
        </div>
    </div>
</x-layout>


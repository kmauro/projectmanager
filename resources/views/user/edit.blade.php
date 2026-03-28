<x-layout>

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

    <div>
        <form action="/user/changepassword" method="post">
            @csrf
            <div class="col-4">
                <label for="user" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('user') is-invalid @enderror" id="user" name="user" autocomplete="off" placeholder="Nombre de usuario" value="{{ old('user') }}" required>
                @error('user')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-4">
                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="off" placeholder="Mínimo 8 caracteres" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">La contraseña debe tener al menos 8 caracteres</small>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
        </form>
    </div>
</x-layout>
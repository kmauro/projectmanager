
<x-layout>
    <div class="row">
        <h1 class="col-3">Registro de Usuarios</h1>
    </div>
    
    <!-- Mensajes de éxito, error e info -->
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

    @if(session('info'))
        <div class="row">
            <div class="col-1"></div>
            <div class="col-4">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulario de registro de usuario -->
    <div class="row">
        <div class="col-1"></div>
        <div class="col-4">
            <form class="row g-3" method="POST" action="/user/register">
                @csrf
                
                <div class="col-12">
                    <label for="user" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('user') is-invalid @enderror" id="user" name="user" autocomplete="off" placeholder="Nombre de usuario" value="{{ old('user') }}" required>
                    @error('user')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" autocomplete="off" placeholder="Nombre completo" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" autocomplete="off" placeholder="correo@ejemplo.com" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" autocomplete="off" placeholder="Mínimo 8 caracteres" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">La contraseña debe tener al menos 8 caracteres</small>
                </div>

                <div class="col-12">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" autocomplete="off" placeholder="(011) 1234-5678" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Registrar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
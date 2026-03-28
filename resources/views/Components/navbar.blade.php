<!-- Sidebar -->


<div class="sidebar expanded d-flex flex-column flex-grow-1 min-h-0" id="sidebar">
        
        {{-- <a href="#" class="menu-item" id="toggleBtn"><i class="navbarIcon fa-solid fa-bars"></i> <h3 class="menu-text">Project Manager</h3> </a>
        <hr> --}}
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">	
                <a href="/" class="menu-item"><i class="navbarIcon fas fa-home"></i> <span class="menu-text">Inicio</span></a>
            </li>
            <li class="nav-item">
                <a href="{{ route('projects.upload') }}" class="menu-item"><i class="navbarIcon bi bi-upload"></i> <span class="menu-text"> Cargar datos </span></a>
            </li>
            <li class="nav-item">
                <a href="{{ route('projects') }}" class="menu-item"><i class="navbarIcon bi bi-search"></i> <span class="menu-text"> Buscador</span></a>
            </li>
            <li class="nav-item">
                <a href="#" class="menu-item"><i class="navbarIcon "></i> <span class="menu-text"> Editar Proyecto</span></a>
            </li>
            
        </ul>
        <hr>
        <div class="dropdown"> 
            <a href="#" class="d-flex align-items-center menu-item link-body-emphasis text-decoration-none dropdown-toggle"
               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                 <i class="bi bi-person-lines-fill" style="color:white;"></i>
                 <span class="menu-text" style="color:white;">
                    @auth
                        {{ auth()->user()->user }}
                    @else
                        Invitado
                    @endauth
                 </span> 
            </a>
            <ul class="dropdown-menu text-small shadow" id="dropdown-menu"> 
                @auth
                    <li><a class="dropdown-item" href="#">Ajustes</a></li> 
                    <li><a class="dropdown-item" href="#">Lista usuarios</a></li> 
                    <li><a class="dropdown-item" href="/changepassword">Cambiar contraseña</a></li> 
                    <li><a class="dropdown-item" href="#">Perfil</a></li> 
                    <li><hr class="dropdown-divider"></li> 
                    <li><a class="dropdown-item" href="{{ route('logout') }}">Cerrar sesión</a></li> 
                @else
                    <li><a class="dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                    <li><a class="dropdown-item" href="{{ url('/user/create') }}">Registrarse</a></li>
                @endauth
            </ul> 
        </div>
    </div>

    
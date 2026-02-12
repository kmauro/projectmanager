<!-- Sidebar -->


<div class="sidebar expanded d-flex flex-column flex-shrink-0  " id="sidebar">
        
        <a href="#" class="menu-item" id="toggleBtn"><i class="fa-solid fa-bars"></i> <h1 class="menu-text">SGC</h1> </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">	
                <a href="/" class="menu-item"><i class="fas fa-home"></i> <span class="menu-text">Inicio</span></a>
            </li>
            
            <li class="nav-item">
                <a href="/stock" class="menu-item"><i class="bi bi-box-seam-fill"></i> <span class="menu-text">Stock</span></a>
            </li>
            <li class="nav-item">
                <a href="#" class="menu-item"><i class=""></i> <span class="menu-text"> Nueva venta </span></a>
            </li>
            
            
            <li class="nav-item">
                <a href="/suppliers" class="menu-item"><i class="bi bi-person-lines-fill"></i> <span class="menu-text">Proveedores</span></a>
            </li>
            
            
            <li class="nav-item">
                <a href="#" class="menu-item"><i class=""></i> <span class="menu-text"> Historial de ventas </span></a>
            </li>
            <li class="nav-item">
                <a href="#" class="menu-item"><i class=""></i> <span class="menu-text"> Contaduria </span></a>
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

    
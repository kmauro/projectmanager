

<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Project Manager</title>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        
        @vite(['resources/sass/app.scss', 'resources/css/sidebar.css', 'resources/js/app.js'])
    </head>
  <body class="app-shell">
    <header class="app-header">
        <x-header></x-header>
    </header>

    <div class="app-body">
        <aside class="app-sidebar" aria-label="Navegación principal">
            <x-navbar></x-navbar>
        </aside>

        <main id="main-content" class="app-content">
            {{ $slot }}
        </main>
    </div>

    <div class="app-footer" role="contentinfo">
        <x-footer></x-footer>
    </div>

    <script src="./bootstrap.bundle.min.js"></script>

    {{-- <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const mainContent = document.getElementById('main-content');
        const dropdownToggle = document.querySelector(".dropdown-toggle");
        const dropdownMenu = document.getElementById('dropdown-menu');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('expanded');

            // Ajustar el margen izquierdo del contenido
            if (sidebar.classList.contains('expanded')) {
                dropdownToggle.setAttribute("data-bs-toggle", "dropdown");
                dropdownToggle.classList.add("dropdown-toggle");
                mainContent.style.marginLeft = "250px"; // Expande el contenido
            } else {
                mainContent.style.marginLeft = "60px"; // Contrae el contenido
                dropdownToggle.removeAttribute("data-bs-toggle");
              dropdownToggle.classList.remove("dropdown-toggle");
              dropdownMenu.classList.remove("show");
            }
        });
    </script> --}}
  </body>
</html>


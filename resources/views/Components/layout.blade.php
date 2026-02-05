<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGC</title>
    
    
    @vite(['resources/sass/app.scss', 'resources/css/sidebar.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


</head>
<body style="background-color:white;">
    <section class="sidebar">
        <x-navbar></x-navbar>
    </section>

    <main id="main-content" style="margin-left:250px;">
        <div>
            {{$slot}}

            
        </div>
    </main>

    <script>
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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>



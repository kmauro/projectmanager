<x-layout>
    <div class="row">
        <h1 class="col-3">Cerrar sesión</h1>
    </div>

    <div class="row mt-3">
        <div class="col-1"></div>
        <div class="col-4">
            <form class="row g-3" method="POST" action="/user/logout">
                @csrf
                <div class="col-12">
                    <p>¿Estás seguro de que deseas cerrar sesión?</p>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-danger">Cerrar sesión</button>
                    <a href="/" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
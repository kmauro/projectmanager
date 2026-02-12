<x-layout>

    <div>
        <form action="/user/changepassword" method="post">
            @csrf
            <label for="user">Nombre de usuario</label>
            <input type="text" name="user" id="user" placeholder="Usuario">
            <label for="password">Nueva contraseña</label>
            <input type="password" name="password" id="password" placeholder="Contraseña">
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
        </form>
    </div>
</x-layout>
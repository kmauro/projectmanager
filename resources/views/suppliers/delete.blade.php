<x-layout>
    <div class = "row"><h1 class="col-3">Eliminar Proveedor</h1> </div>
<!-- Formulario de proveedor -->
    <div class="row">
        <form action="/supplier/delete/{{ $supplier->id }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $supplier->id }}">
            <p>¿Está seguro que desea eliminar el proveedor <strong>{{ $supplier->name }}</strong>?</p>
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <a href="/suppliers" class="btn btn-secondary">Cancelar</a>
        </form>
</x-layout>
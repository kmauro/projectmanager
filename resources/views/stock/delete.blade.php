<x-layout>
    <div class = "row"><h1 class="col-3">Eliminar Producto</h1> </div>
<!-- Formulario de proveedor -->
    <div class="row">
        <form action="/product/delete/{{ $product->id }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $product->id }}">
            <p>¿Está seguro que desea eliminar el producto <strong>{{ $product->name }}</strong>?</p>
            <button type="submit" class="btn btn-danger">Eliminar</button>
            <a href="/stock" class="btn btn-secondary">Cancelar</a>
        </form>
</x-layout>
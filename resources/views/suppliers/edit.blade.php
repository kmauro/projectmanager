<x-layout>
    <div class = "row"><h1 class="col-3">Editar Proveedor</h1> </div>
<!-- Formulario de proveedor -->
	<div class="row">
        <div class  = "col-1"></div>
        <div class="col-4">
            <form class="row g-3" method="POST" action="/supplier/edit/{{ $supplier->id }}">
                @csrf
                <div class="col-12">
                    <label for="name" class="form-label">Nombre Proveedor</label>
                    <input type="text" class="form-control" id="name" name="name" autocomplete=off placeholder="Nombre del Proveedor" value="{{ $supplier->name }}" >
                </div>

                <div class="col-md-6">
                    <label for="cuit" class="form-label">CUIT</label>
                    <input type="text" class="form-control" id="cuit" name="cuit" autocomplete=off placeholder="99-99999999-9" value="{{ $supplier->cuit }}" >
                </div>

                <div class="col-md-6">
                    <label for="phone" class="form-label">Telefono</label>
                    <input type="text" class="form-control" id="phone" name="phone" autocomplete=off placeholder="(011) 1234-5678" value="{{ $supplier->phone }}" >
                </div>

                <div class="col-12">
                    <label for="email" class="form-label">e-mail</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="yourname@host.com" value="{{ $supplier->email }}" >
                </div>

                <div class="col-12">
                    <label for="address" class="form-label">Direccion</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Calle Falsa 123" value="{{ $supplier->address }}" >
                </div>


                <div class="col-12">
                    <button type="submit" class="btn btn-primary"> Actualizar </button>
                </div>
            </form>
        </div>
    </div>

</x-layout>
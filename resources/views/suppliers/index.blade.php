<x-layout>
	<div class="row">
		<h1 class="col-2">Proveedores</h1>
		<?php
			//if($_SESSION["access_level"] == 1 && empty($_GET["id"])){
				echo '<a class = "col-1" href="/supplier/add"><button type="button" class="btn btn-primary">Agregar</button></a>';
			//}
		?>
	</div>

	<table class="table table-dark  table-bordered table-striped">
		
		<thead>
			
			<tr>
				
				<th>Nombre</th>
				<th>CUIT</th>
				<th>email</th>
				<th>telefono</th>
				<th>Direccion</th>
				<th></th>

			</tr>

		</thead>

		<tbody>
			
			

			
                @foreach ($suppliers as $supplier)
                <tr>
                    <td> {{ $supplier->name }} </td>
                    <td> {{ $supplier->cuit }} </td>
                    <td> {{ $supplier->email }} </td>
                    <td> {{ $supplier->phone }} </td>
                    <td> {{ $supplier->address }} </td>
                    <td><a href="/supplier/edit/{{ $supplier->id }}">Editar</a> | <a href="/supplier/delete/{{ $supplier->id }}">Eliminar</a></td>
                </tr>
                @endforeach



		</tbody>

	</table>

	<div>{{ $suppliers->links() }}</div>

	<style>
		.row{
			padding-bottom: 20px;
		}

		.table {
			padding-top: 25px;
			border-radius: 8px;
			overflow: hidden; /* Evita que los bordes se corten */
			box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
		}
	</style>

</x-layout>
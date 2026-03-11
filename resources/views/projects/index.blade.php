<x-layout>
    <h1>Proyectos</h1>

    <!-- Aquí va el contenido principal de la página -->
    <div class="main-content">
        <table class="table table-striped table-bordered table-dark">
            <thead>
                <tr>
                    <th class="col-md-1">ID</th>
                    <th class="col-md-3">Nombre</th>
                    <th class="col-md-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->title }}</td>
                        <td>
                            <a href="#" class="btn btn-primary">Ver más <i class="fas fa-eye"></i></a>
                            <a href="#" class="btn btn-primary">Editar <i class="fas fa-edit"></i></a>
                            <a href="#" class="btn btn-danger">Eliminar <i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                 @endforeach
            </tbody>
        </table>
        {{ $projects->links() }}
    </div>
</x-layout>
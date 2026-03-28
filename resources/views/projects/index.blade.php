<x-layout>

    <!-- Modal de filtros -->
    <div class="modal fade" id="filtersModal" tabindex="-1" aria-labelledby="filtersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content text-start">
                <div class="modal-header">
                    <h5 class="modal-title" id="filtersModalLabel">Filtros</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ url()->current() }}" method="GET">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="filterOption1" name="filter_option_1">
                            <label class="form-check-label" for="filterOption1">Filtro 1</label>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="filterOption2" name="filter_option_2">
                            <label class="form-check-label" for="filterOption2">Filtro 2</label>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" value="1" id="filterOption3" name="filter_option_3">
                            <label class="form-check-label" for="filterOption3">Filtro 3</label>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-success btn-sm">Aplicar</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-between">
        <div class="col-7">
            <h1>Proyectos</h1>
        </div>
        <div class="col-5 ">
            <div class="row ">
                <form class="row g-1" action="#">
                    <div class="col-2"></div>
                    <div class="col-4">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    </div>
                    <div class="col-4">
                        <select name="searchField" class="form-control" id="searchField" >
                            <option value="1">Seleccionar campo</option>
                            <option value="1">Titulo</option>
                            <option value="2">Solicitante</option>
                            <option value="3">Nivel TRL</option>
                            <option value="4">Provincia</option>
                        </select>
                    </div>
                    <div class="col-1">
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#filtersModal">
                            <i class="bi bi-funnel"></i>
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
    
    

    
    


    
    <!-- contenido principal de la página -->
    <div class="row">
        <div class="main-content">
            <table class="table table-striped table-bordered table-dark">
                <thead>
                    <tr>
                        <th class="col-md-1">ID</th>
                        <th class="col-md-6">Nombre</th>
                        <th class="col-md-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td>{{ $project->id }}</td>
                            <td>{{ $project->title }}</td>
                            <td>
                                <a href="{{ route('project.view', ['id' => $project->id]) }}" class="btn btn-primary"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('project.edit', ['id' => $project->id]) }}" class="btn btn-secondary"><i class="fas fa-edit"></i></a>
                                <a href="#" class="btn btn-danger"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $projects->links() }}
        </div>
    </div>
</x-layout>
<x-layout>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <h1>Crear Proyecto</h1>

    <div class="main-content">
        <form action="{{ route('projects.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
        
            <div class="mb-3">
                <label class="form-label">Seleccionar archivo CSV</label>
                <input type="file" name="file" class="form-control" accept=".csv">
            </div>
        
            <button type="submit" class="btn btn-primary">
                Importar archivo
            </button>
        </form>
    </div>
</x-layout>
@php
    $categories = App\Models\Category::all();
@endphp


<x-layout>
    <div class = "row"><h1 class="col-3">Agregar Producto</h1> </div>
    <div class="row">
        <form method="POST" class="row g-3" action="/product/add">
            @csrf
            <div class="col-12">
                <label for="name" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="col-md-6">
                <label for="category" class="form-label">Categoría</label>
                <select class="form-select" name="category" id="category">
                    <option value="">Seleccione una categoría</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label for="subcategory_id" class="form-label">Subcategoría</label>
                <select class="form-select" name="subcategory_id" id="subcategory_id">
                    <option value="">Seleccione una subcategoría</option>
                </select>
            </div>
            
            <div class="col-md-6">
                <label for="cost" class="form-label">Costo</label>
                <input type="number" step="0.01" class="form-control" id="cost" name="cost" required>
            </div>
            <div class="col-md-6">
                <label for="price" class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price"  required>
            </div>


            <div class="col-md-6">
                <label for="stock" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="stock" name="stock"  required>
            </div>
            <div class="col-md-6">
                <label for="min_stock" class="form-label">Stock Mínimo</label>
                <input type="number" class="form-control" id="min_stock" name="min_stock" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Agregar Producto</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('category').addEventListener('change', function() {
            let categoryId = this.value;
            let subcategorySelect = document.getElementById('subcategory_id');
            subcategorySelect.innerHTML = '<option value="">Cargando...</option>';

            if (categoryId) {
                fetch(`/subcategories/${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        subcategorySelect.innerHTML = '<option value="">Seleccione una subcategoría</option>';
                        data.forEach(subcat => {
                            subcategorySelect.innerHTML += `<option value="${subcat.id}">${subcat.name}</option>`;
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar subcategorías:', error);
                        subcategorySelect.innerHTML = '<option value="">Error al cargar</option>';
                    });
            } else {
                subcategorySelect.innerHTML = '<option value="">Seleccione una categoría primero</option>';
            }
        });
    </script>


</x-layout>


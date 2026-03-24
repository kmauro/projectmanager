<x-layout>
    @php
        
        $applicant = $project['applicant'];
        $project = $project['project'];
        
    @endphp
    <div class="row contenido">
        <div class ="row">
            <h3 class="col-3">Proyecto</h3> 
            <div class="col-2">
                <a href="/product/delete/" class="btn btn-danger">Eliminar Proyecto</a>
            </div>
        </div>
        <div class="row">
            <form method="POST" class="row g-3" action="/product/edit/">
                @csrf
                <div class="col-12">
                    <label for="name" class="form-label">Titulo</label>
                    
                    <input type="text" class="form-control" id="name" name="name" value="{{ $project->title }}" required>
                    
                </div>
                <div class="col-md-6">
                    <label for="start_date" class="form-label">Fecha inicio</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" value="{{$project->start_date}}" required>
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label">Fecha Fin</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" value="{{$project->end_date}}" required>
                </div>
                <div class="col-12">
                    <label for="objective" class="form-label">Objetivo del proyecto</label>
                    <textarea class="form-control" id="objective" rows="3"> {{$project->objective}} </textarea>
                </div>
                <div class="col-12">
                    <label for="innovation_value" class="form-label">Diferencia innovativa</label>
                    <textarea class="form-control" id="innovation_value" rows="3"> {{$project->innovation_value}} </textarea>
                </div>
                
                <div class="col-md-6">
                    <label for="trl_level" class="form-label">Nivel TRL</label>
                    <input type="text" class="form-control" id="trl_level" name="trl_level" value="{{$project->trl_level_id}}" required>
                </div>

                <div class="col-md-6">
                    <label for="area" class="form-label">Vertical</label>
                    <input type="text" class="form-control" id="area" name="area" value="{{$project->area_id}}" required>
                </div>

                <div class="col-md-6">
                    <label for="located_entity" class="form-label">Entidad donde se encuentra radicado</label>
                    <input type="text" class="form-control" id="located_entity" name="located_entity" value="{{$project->located_entity}}" required>
                </div>

                <div class="col-md-6">
                    <label for="province" class="form-label">Provincia</label>
                    <input type="text" class="form-control" id="province" name="province" value="{{$project->province_id}}" required>
                </div>

                <div class="col-12">
                    <label for="EBCT_amount" class="form-label">Monto de financiamiento EBCT/Startup</label>
                    <input type="text" class="form-control" id="EBCT_amount" name="EBCT_amount" value="{{$project->EBCT_amount}}" required>
                </div>

                
                <div class="col-12">
                        <button type="submit" class="btn btn-primary">Actualizar Proyecto</button>
                </div>
            </form>
        </div> 
    </div>   

    
    

</x-layout>
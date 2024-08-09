@extends('layouts.app', ['activePage' => 'reportes', 'titlePage' => __('Reportes')])

@section('content')

  <div class="content">
    <div class="container-fluid">
      <div class="row">

        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                        <h4 class="card-title ">Reportes</h4>
                        <p class="card-category"> Reportes adicionales de la plataforma</p>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12 ">
                        
                        <!--  
                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">
                            <span class="material-icons">
                                add_circle_outline
                            </span>
                            Nuevo Reporte
                        </a>
                        -->
                    </div>
                </div>
            </div>
            
            
            </div>
            
            <div class="card-body">

                  <form action="{{ route('reporte') }}" method='POST' >
                     @csrf

                    <section class="FormularioCampos">
                        <div class="Campo">
                            <select name='tipo' class="form-control">
                             	<option value=''>Elegir tipo</option>
                             	<option value='Tiempossub'>Tiempos en Subprocesos</option>
                             	<!--  <option value='Requisiciones'>Reporte de Requisiciones</option>  -->
                             </select>
                             <br/>
                             <label>Tipo</label>

                        </div>
                        <div class="Campo">
                            <input type="text" name="desde" class="form-control datetimepicker"  size='10' maxlength='10'  
                            value="<?php echo App\Libraries\Tools::valor("desde",$desdeDef); ?>">
                            <label>Desde</label>

                        </div>
                        <div class="Campo">

                            <input type="text" name="hasta" class="form-control datetimepicker"  size='10' maxlength='10' 
                             value="<?php echo App\Libraries\Tools::valor("hasta",$hastaDef); ?>">
                             <label>Hasta</label>

                        </div>
                    </section>

                    <br/>
                    <div class="">
                     	<button class="bot" id="botConsultar" disabled="disabled">Consultar</button>
                    </div>                     
                     
                     </form>  


            </div>
            
    	</div>
	</div>
</div>

<link rel="stylesheet" href="{{ url('/') }}/css/reportes.css">


@endsection



@push('js')
<script type='text/javascript'>



 $(document).ready(function(){

 	$("[name='tipo']").change(function(){
	var val = $(this).val();
	if(val==""){return;}
	$("#botConsultar").prop("disabled",false);

 	});

 	
 	 $('.datetimepicker').datetimepicker({
         format: 'YYYY-MM-DD'
     });

});

 </script>
@endpush
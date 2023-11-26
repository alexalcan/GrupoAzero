@extends('layouts.app', ['activePage' => 'reportes', 'titlePage' => __('Reportes')])

@section('content')
<?php var_dump($tipo); ?>
  <div class="content">
    <div class="container-fluid">
      <div class="row">

        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                        <h4 class="card-title ">Reporte</h4>
                        <p class="card-category"> Reporte de la plataforma</p>
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




            </div>
            
    	</div>
	</div>
</div>




@endsection



@push('js')
<script>
 $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });

 </script>
@endpush
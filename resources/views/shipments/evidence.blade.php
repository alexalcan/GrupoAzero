@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Evidencia de material terminado')])


@section('content')
<link rel='stylesheet' type='text/css' href='{{ url("/") }}/css/attachlist.css' />
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('shipments.store') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="type" value="evidence">
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Subir evidencia de material terminado</h4>
                            <p class="card-category">Pedido {{ $order->invoice }}</p>
                        </div>
                        <div class="card-body ">
                        
     						<section class='attachList' rel='ever' 
     						uploadto="{{ url('order/attachpost?catalog=shipments') }}" 
     						href="{{ url('order/attachlist?rel=ever&catalog=shipments&order_id='.$order->id) }}"></section> 
     						  
                        </div>

                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                    </form>
                </div>
                </div>
        </div>
    </div>





@endsection


@push('js')
<script type='text/javascript' src='{{ url("/") }}/js/attachlist.js'></script>
<script type='text/javascript'>

$(document).ready(function(){
    
    AttachList("ever");

});


</script>

@endpush



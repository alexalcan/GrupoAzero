<?php
use App\Pedidos2;


?>
@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Administrar Pedidos')])

@section('content')
<?php
$statuses = Pedidos2::StatusesCat();

?>

<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css?x=".rand(0,999)."') }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/pedido.css') }}" />

<main class="content">

    <div class="card">

    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Pedido {{ $pedido->invoice }}</h4>
         </div>
    </div>

<p>&nbsp;</p>

    <form action="{{ url('pedidos2/guardar/'.$pedido->id) }}">

        <fieldset class='MainInfo'>
        <div class='FormRow'><label>Folio</label><input type="text" name="invoice_number" value="{{$pedido->invoice_number}}" /></div>
        <div class='FormRow'><label># Factura</label><input type="text" name="invoice" value="{{$pedido->invoice}}" /></div>
        <div class='FormRow'><label>Cliente</label><input type="text" name="client" value="{{$pedido->client}}" /></div>
        <div class='FormRow'><label></label><span><button>Guardar</button></span></div>
        </fieldset>

        

        <fieldset class='MiniInfo'>
            <div><label>Folio</label><span>{{$pedido->invoice_number}}</span></div>
            <div><label># Factura</label><span>{{$pedido->invoice}}</span></div>
            <div><label>Cliente</label><span>{{$pedido->client}}</span></div>
            <div><label></label><a class="powerLink" onclick="MostrarMainInfo()">Cambiar datos principales</a></div>
        </fieldset>

        
    </form>

    
    <div class="BigEstatus E{{ $pedido->status_id }}"><span >{{$pedido->status_name}}</span></div>


    <div class="Cuerpo">
        
        <h4 class="Fila card-title">Cambiar Estatus</h4>
    
        <div class="Cambio">
            <select name="status_id" >
                <option value="">-Elige el siguiente estatus-</option>
                @foreach ($statuses as $k=>$v)
                <option value="{{$k}}">{{$v}}</option>
                @endforeach
            </select>

        </div>


    </div>



</div>









<div class="card">
    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Parcial</h4>
         </div>
    </div>

    <p>&nbsp;</p>
</div>


</main>

{{ $id }}

<?php var_dump($pedido); ?>

@endsection

@push('js')
<script>
$(document).ready(function(){
    




});


 function MostrarMainInfo(){
    $(".MainInfo").slideDown();
    $(".MiniInfo").hide();
 }


</script>    
@endpush
<?php
use App\Libraries\Tools;

$origins=["F"=>"Factura","C"=>"Cotización","R"=>"Requisición"];

?>
<aside class="ShipsLista">
    @foreach ($shipments as $ship)
    <div class="Pedido" rel="{{ $ship->id }}" del="{{ url('pedidos2/set_status/'.$ship->id) }}">
        <div class="estatus E{{$ship->status_id}}">{{ $statuses[$ship->status_id]}}</div>
        <div class="Cont">
            <div class="factura"> <label>Factura # </label> {{ $ship->invoice_number }}</div>
            <div class="cot"> <label>Cotizacion #</label>  {{ $ship->invoice }}</div>
            <div class="office"> <label>Sede</label>  {{ $ship->office }}</div>
            <div class="origin"> <label>Origin</label>  {{ $ship->origin }}</div>
            <div class="client"> <label>Cliente</label> {{ $ship->client }}</div>
            <div class="createdat"> <label>Creación</label> {{ Tools::fechaMedioLargo($ship->created_at) }}</div>
        </div>

    </div>
    @endforeach
</aside>
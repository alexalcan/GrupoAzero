<?php
use App\Libraries\Tools;

$origins=["F"=>"Factura","C"=>"Cotización","R"=>"Requisición"];

?>
<aside class="ShipsLista">
    @foreach ($lista as $ship)
    <div class="Pedido" rel="{{ $ship->id }}" del="{{ url('pedidos2/set_status/'.$ship->id) }}">
        <div class="estatus E{{$ship->status_id}}">{{ $statuses[$ship->status_id]}}</div>
        <div>&nbsp;</div>
        <div class="FilaMorder morder">
            <div class="num"> <label>Número Orden # </label> <div>{{ $ship->number }}</div> </div>
            <div class="order"> 
                @if (empty($ship->invoice_number))
                <label>Cotizacion #</label> <div>{{ $ship->invoice }}</div> 
                @else
                <label>Factura #</label> <div> {{ $ship->invoice_number }}</div>
                @endif
            </div>
            <div class="createdat"> <label>Creación</label> <div> {{ Tools::fechaMedioLargo($ship->created_at) }}</div></div>
        </div>

    </div>
    @endforeach
</aside>
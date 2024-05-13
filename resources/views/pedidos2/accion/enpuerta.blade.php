<form action="{{ url('pedidos2/set_accion/'.$id.'?a=enpuerta') }}" id="FSetAccion" method="post">
@csrf 


@if ($paso == 1)
<input type="hidden" name="paso" value="1"/>
<input type="hidden" name="shipment" value="0"/>
<nav class="SubAccion">
    <button rel="shipment" class="setto" val="1">Chofer Entrega</button>
    <button rel="shipment" class="setto" val="0">Cliente Recoge</button>
</nav>

@elseif ($paso == 2)
<section class='attachList form-control' rel='entr' uploadto="{{ url('pedidos2/attachlist?catalog=pictures&order_id='.$order->id) }}" 
href="{{ url('pedidos2/attachlist?rel=entr&catalog=pictures&order_id='.$order->id) }}"></section> 

<aside class="AccionForm">
    <div class="Fila"><input type="submit" name="sb" class="form-control" value="Guardar" /> </div>
</aside>

@endif



</form>
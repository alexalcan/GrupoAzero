<form action="{{ url('pedidos2/set_accion/'.$id.'?a=enpuerta') }}" id="FSetAccion" method="post">
@csrf 


@if ($paso == 1)
<input type="hidden" name="paso" value="1"/>
<input type="hidden" name="shipment" value="0"/>
<nav class="SubAccion">
    <button rel="shipment" class="setto" val="1">Envio</button>
    <button rel="shipment" class="setto" val="1">Entrega Directa</button>
</nav>

@elseif ($paso == 2)
<section class='attachList' rel='ever' uploadto="{{ url('pedidos2/attachlist?catalog=pictures&order_id='.$order->id) }}" 
href="{{ url('pedidos2/attachlist?rel=ever&catalog=pictures&order_id='.$order->id) }}"></section> 

<aside class="AccionForm">
    <div class="Fila"><input type="submit" name="sb" value="Guardar" /> </div>
</aside>

@endif



</form>
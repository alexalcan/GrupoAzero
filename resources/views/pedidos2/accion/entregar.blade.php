@if ($paso == 1)

<form action="{{ url('pedidos2/set_accion/'.$id.'?a=entregar') }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1"/>
<input type="hidden" name="shipment" value="0"/>
<nav class="SubAccion">
    <button rel="shipment" class="setto" val="1">Agregar Evidencia</button>
    <button rel="shipment" class="setto" val="0">Terminar</button>
</nav>

</form>

@endif



@if ($paso == 2)

<form action="{{ url('pedidos2/set_accion/'.$id.'?a=entregar') }}" id="FSetAccion" method="post">
@csrf 
<h3>Agregar imágenes de la entrega</h3>

<section class='attachList form-control' rel='ent' uploadto="{{ url('pedidos2/attachlist?catalog=pictures&event=entregar&order_id='.$order->id) }}" 
href="{{ url('pedidos2/attachlist?rel=ent&event=entregar&catalog=pictures&order_id='.$order->id) }}"></section> 

<aside class="AccionForm">
    <div class="Fila"><input type="submit" class="form-control" name="sb" value="Guardar" /> </div>
</aside>


</form>

@endif




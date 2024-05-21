@if ($paso == 1)

<form action="{{ url('pedidos2/set_accion/'.$id.'?a=entregar') }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1"/>
<input type="hidden" name="evidencia" value="0"/>
<nav class="SubAccion">
    <button rel="evidencia" class="setto" val="1">Agregar Evidencia</button>
    <button rel="evidencia" class="setto" val="0">Terminar</button>
</nav>

</form>

@endif



@if ($paso == 2)

<div class="FormaAccion">
<label>Agregar Evidencias</label>
<section class='attachList form-control' rel='ent' uploadto="{{ url('pedidos2/attachlist?catalog=pictures&event=entregar&order_id='.$order->id) }}" 
href="{{ url('pedidos2/attachlist?rel=ent&event=entregar&catalog=pictures&order_id='.$order->id) }}"></section> 

<div><input type="button" class="btn" value="Terminar" onclick="MiModal.exit()" /></div>
</div>

@endif




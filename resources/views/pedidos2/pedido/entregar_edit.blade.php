<div class="FormaAccion">
<label>Agregar Evidencias</label>
<section class='attachList form-control' rel='ent' 
uploadto="{{ url('pedidos2/attachlist?catalog=pictures&event=entregar&order_id='.$order->id) }}" 
href="{{ url('pedidos2/attachlist?rel=ent&event=entregar&catalog=pictures&order_id='.$order->id) }}"></section> 

<div><input type="button" class="btn" value="Terminar" onclick="MiModal.exit()" /></div>
</div>
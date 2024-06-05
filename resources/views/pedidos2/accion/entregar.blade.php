
<div class="FormaAccion">

<label>Agregar Evidencias</label>
<p>Es requisito subir evidencia, incluyendo foto o escaneo de Factura Amarilla firmada por el cliente.</p>

<section class='attachList form-control' rel='entregar' uploadto="{{ url('pedidos2/attachlist?catalog=pictures&event=entregar&order_id='.$order->id) }}" 
href="{{ url('pedidos2/attachlist?rel=entregar&event=entregar&catalog=pictures&order_id='.$order->id) }}"></section> 

<div class="Fila" id="filaTerminarEntrega" ><input type="button" class="form-control" value="Terminar" onclick="MiModal.exit()" /></div>
</div>

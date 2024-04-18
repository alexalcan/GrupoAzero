<form action="{{ url('pedidos2/set_accion/'.$id.'?a=entregar') }}" id="FSetAccion" method="post">
@csrf 


<section class='attachList' rel='ent' uploadto="{{ url('pedidos2/attachlist?catalog=pictures&event=entregar&order_id='.$order->id) }}" 
href="{{ url('pedidos2/attachlist?rel=ent&event=entregar&catalog=pictures&order_id='.$order->id) }}"></section> 

<aside class="AccionForm">
    <div class="Fila"><input type="submit" name="sb" value="Guardar" /> </div>
</aside>


</form>
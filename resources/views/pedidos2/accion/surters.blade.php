<form action="{{ url('pedidos2/set_accion_surters/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<aside class="AccionForm">
    <div class="Fila">¿Confirma que el pedido con requisición <b>{{ $stockreq["number"] }}</b> fue surtido?</div>
    <div class="Fila"><input type="submit" class="form-control" name="sb" value="Confirmar" /> </div>
</aside>

</form>
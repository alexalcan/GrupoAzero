<form action="{{ url('pedidos2/set_accion/'.$id.'?a=fabricado') }}" id="FSetAccion" method="post">
@csrf 
<aside class="AccionForm">
    <div class="Fila">¿Confirma que el pedido fue fabricado?</div>
    <div class="Fila"><input type="submit" name="sb" value="Confirmar" /> </div>
</aside>


</form>
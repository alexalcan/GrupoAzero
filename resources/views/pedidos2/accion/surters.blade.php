<form action="{{ url('pedidos2/set_accion_surters/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<aside class="AccionForm">
    <div class="Fila">¿Confirma que el pedido con requisición <b>{{ $stockreq["number"] }}</b> pasó por puerta?</div>

    <div class="Fila">

        <section id='enPuertaEv' rel='enpuerta' 
        uploadto="{{ url('pedidos2/attachlist') }}" 
        href="{{ url('pedidos2/attachlist') }}" 
        catalog='pictures' 
        key='order_id' 
        value='{{ $order->id }}' event='enpuerta'  ></section>
        <!-- AttachListCreate(contenedorPath,rel,uploadto, listHref,catalog,key,value, mode,event,callback) -->
    </div>

    <div class="Fila"><input type="submit" class="form-control" name="sb" value="Confirmar" /> </div>
</aside>

</form>
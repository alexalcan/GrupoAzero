<?php
use App\Pedidos2;
?>
<h3>Deshacer auditoria de {{Pedidos2::CodigoDe($order)}}</h3>
<form action="{{url('pedidos2/set_accion_desauditoria/'.$order->id)}}" method="post">
@csrf 
<div class="Fila">
    <label>Comentario </label>
    <div>
        <textarea name="comentario" rows="3" cols="56" maxlength="162"></textarea>
        <br/>
        <small>(Máximo 160 caracteres)</small>
</div>
</div>

<div class="Fila"><input type="submit" value="Continuar" /></div>

</form>
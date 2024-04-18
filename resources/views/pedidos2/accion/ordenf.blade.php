<form action="{{ url('pedidos2/set_accion/'.$id.'?a=ordenf') }}" id="FSetAccion" method="post">
@csrf 
<aside class="AccionForm">
    <div class="Fila">Adjunte la orden de fabricación.</div>
    <div class="Fila"><label>Archivo</label><input type="file" name="archivo" /></div>
    <div class="Fila"><input type="submit" name="sb" value="Agregar" /> </div>
</aside>


</form>
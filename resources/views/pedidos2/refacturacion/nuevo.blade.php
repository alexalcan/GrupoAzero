<form action="{{ url('pedidos2/refacturacion_crear/'.$order_id) }}" id="FSetAccion" method="post">
@csrf 
<aside class="AccionForm">
    
    <div class="Fila">Adjunte la evidencia de la refacturación.</div>

    <!-- <div class="Fila"><label>Número</label><input type="input" name="number" class="form-control" maxlength="190" /></div> -->
    
    <div class="Fila"><label>Razón</label>
    <select name="reason_id" class="form-control" >
        @foreach ($reasons as $rea)
        <option value="{{$rea->id}}">{{ $rea->reason }}</option>
        @endforeach
    </select>
    </div>


    <div class="Fila"><label>Archivo</label><input type="file" name="file" class="form-control" /></div> 
    

    <div class="Fila"><input type="submit" name="sb" value="Agregar" class="form-control" /> </div>

</aside>


</form>
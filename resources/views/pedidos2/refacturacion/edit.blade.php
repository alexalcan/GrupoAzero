<form action="{{ url('pedidos2/refacturacion_update/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<aside class="AccionForm">
    
    <h4>Refacturación.</h4>

    <!-- <div class="Fila"><label>Número</label><input type="input" name="number" class="form-control" maxlength="190" /></div> -->
    
    <div class="Fila"><label>Razón</label>
    <select name="reason_id" class="form-control" >
        @foreach ($reasons as $rea)
        <option value="{{$rea->id}}" {{ ($rea->id == $ob->reason_id) ? "selected" : "" }} >{{ $rea->reason }}</option>
        @endforeach
    </select>
    </div>


    <div class="Fila"><label>Folio Nuevo</label>
    <input type="text" name="number" class="form-control" maxlength="24" value="{{ $ob->number }}" /></div> 

    <div class="Fila"><label>Liga Nueva Factura</label>
    <input type="text" name="url" class="form-control" maxlength="90" value="{{ $ob->url }}" /></div> 

    <div class="Fila"><label>Archivo</label>
    <div>{{ !empty($evidence) ? view('pedidos2/view_storage_item',["path"=>$evidence->file]) : "" }}</div>
    <input type="file" name="file" class="form-control" />
    </div> 
    

    <div class="Fila"><input type="submit" name="sb" value="Agregar" class="form-control" /> </div>

</aside>


</form>
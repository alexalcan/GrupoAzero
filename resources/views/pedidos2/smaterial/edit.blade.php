<?php
$estatuses = [5=>"En Puerta", 6=>"Entregado", 7=>"Cancelado"];
if($user->role_id == 1 || in_array($user->department_id,[4,5])){
    $estatuses[4]="Elaborada";
}
ksort($estatuses);

?>
<form action="{{ url('pedidos2/smaterial_update/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />

<input type="hidden" name="uploadto" value="{{ url('pedidos2/attachpost') }}" />
<input type="hidden" name="listHref" value="{{ url('pedidos2/attachlist') }}" />
<input type="hidden" name="smaterial_id" value="{{ $id }}" />
<input type="hidden" name="urlSetStatus" value="{{ url('pedidos2/set_smaterial_status/'.$id) }}" />

<aside class="AccionForm">

    <h4>Salida de Material</h4>
    
    <div class="Fila doscol"><label>Código</label> <span>{{$ob->code}}</span> </div>

    <div class="Fila doscol"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        $sel = ($ob->status_id == $k) ?"selected" : "";
        echo "<option $sel value='$k'>$v</option>";
        }
        ?>
    </select>
    </div>


    <div class="Fila" id="filaContinuar">
        <input type="button" class="form-control" id="continuarEdit" value="Continuar" />
    </div>

    <div class="monitor"></div>


    <div class="Fila agregarImagenes" style="display: none;"><label>Agregar Imágenes</label></div>

    <div id="alContenedor"></div>
    
    <div class="Fila terminarEdit" style="display: none;"><input type="submit" name="sb" class="form-control" value="Guardar" /> </div>

</aside>


</form>
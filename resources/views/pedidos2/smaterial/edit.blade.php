<?php
$estatuses = [5=>"En Puerta", 6=>"Entregado", 7=>"Cancelado"];
if($user->role_id == 1 || in_array($user->department_id,[4,5])){
    $estatuses[4]="Fabricado";
}
ksort($estatuses);

?>
<form action="{{ url('pedidos2/smaterial_update/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    
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


    <div class="Fila"><label>Agregar Imágenes</label></div>

    <div id='atlSlot'  val="{{$ob->id}}"
        uploadto="{{ url('pedidos2/attachpost') }}" 
        listHref="{{ url('pedidos2/attachlist') }}">
    </div>  
    
    <div class="Fila "><input type="submit" name="sb" class="form-control" value="Guardar" /> </div>

</aside>


</form>
<?php
$estatuses = [1=>"Elaborada",3=>"En Fabricación", 4=>"Fabricado"];
if($user->role_id == 1 ){
    $estatuses[7] = "Cancelado";
}

?>
<form action="{{ url('pedidos2/ordenf_update/'.$id) }}" id="FSetAccion" method="post">
@csrf 
<input type="hidden" name="paso" value="1" />
<aside class="AccionForm">
    
    <div class="Fila doscol"><label>Número</label> <span>{{$ob->number}}</span> </div>

    <div class="Fila"><label>Estatus</label>
    <select class="form-control" name="status_id">
        <?php
        foreach ($estatuses as $k=>$v) {
        $selected = ($k == $ob->status_id) ? "selected" : "";
        echo "<option value='$k' $selected >$v</option>";
        }
        ?>
    </select>
    </div>

    <div class="monitor"></div>

    <div class="Fila" rel="archivo"><label>Documento</label>
    <div>
        <div>
            @if (isset($ob->document))
        {{ view('pedidos2/view_storage_item',['path'=>$ob->document]) }}
        @endif
    </div>
        <div><input type="file" name="document" class="form-control" /> </div>
    </div>

    </div>

    <div class="Fila" rel="archivoc"><label>Documento Cancelaci[on</label>
        <div>
            <div>
            @if (isset($ob->documentc))
            {{ view('pedidos2/view_storage_item',['path'=>$ob->documentc]) }}
            @endif
            </div>
            <div><input type="file" name="documentc" class="form-control" /> </div>
        </div>
    </div>

    
    <div class="Fila "><input type="submit" name="sb" class="form-control" value="Guardar" /> </div>

</aside>


</form>
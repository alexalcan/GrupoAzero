<?php 
function TypeOfFile(string $name) : string{
    if(empty($name)){return "";}
    $pp = explode(".",$name);
    $ext = strtolower(array_pop($pp));
    if($ext == "pdf"){
    return "pdf";    
    }else{
    return "image";
    }
}

function IconOf(string $name){
$type = TypeOfFile($name);
$iconHtml="X";
switch($type){
    case "pdf":
        $iconHtml="<a class='atticon doc' href='".asset("storage/".$name) ."' target='_blank'><img src='".url("/")."/img/pdf.png' height='40' /><br/>".basename($name)."</a>";
        break;
    case "image":
        $iconHtml="<img class='atticon' src='".asset("storage/".$name) ."' />";
        break;
    default:
        $iconHtml="";
        break;
}
return $iconHtml;
}

?>

<section class='attachlist' rel='{{$rel}}' 
uploadto="{{ url('order/attachpost?catalog=' . $catalog) }}" 
href="{{ $url }}">

<ul>
@foreach ($list as $li)

    @if ($catalog == "pictures") 
    <li class='attachitem'>
    <img class='atticon' src='{{ asset("storage/".$li->picture) }}' />
    <div class='delspace'><a class="delatt" href="{{ url("order/attachdelete?catalog=".$catalog."&id=".$li->id) }}" title="Eliminar imagen">X</a></div>    
    </li>
    
    @elseif ($catalog == "evidence") 
    <li class='attachitem'>
    {!! IconOf($li->file) !!}
    <div class='delspace'><a class="delatt" href="{{ url("order/attachdelete?catalog=".$catalog."&id=".$li->id) }}" title="Eliminar Evidencia">X</a></div>
    </li>
    
    @elseif ($catalog == "shipments") 
    <li class='attachitem'>
     {!! IconOf($li->file) !!}
    <div class='delspace'><a class="delatt" href="{{ url("order/attachdelete?catalog=".$catalog."&id=".$li->id) }}" title="Eliminar Evidencia de Embarque">X</a></div>
    </li>    
    @endif
    
@endforeach   
</ul>    


<table><tr>
<td>Agregar</td>
<td><input type='file' name='attachUpload' class="form-control-file"  accept="capture=camera,image/*,.pdf" /></td>
<td><button class='MyAttAdder'>Subir Imagen</button> 
</td>
<td><div class="attachMonitor"></div></td>
</tr></table>

<input type='hidden' name='_token' value='{{ csrf_token() }}' />
@foreach ($urlParams as $k=> $v)
	@if (!empty($v))
	<input type='hidden' class='param' name='{{$k}}' value='{{ $v }}' />
	@endif
@endforeach

</section>

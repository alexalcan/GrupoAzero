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
        //$iconHtml="<a class='atticon doc' href='".asset("storage/".$name) ."' target='_blank'><img src='".url("/")."/img/pdf.png' height='40' /><br/>".basename($name)."</a>";
        $iconHtml ="<a class='atticon pdf' href='".asset("storage/".$name) ."' target='_blank'>
        <embed src='".asset("storage/".$name) ."' alt='' style='width: 100%; height: auto;'></embed>
        <br/>".basename($name)."</a>";
        break;
    case "image":
        $iconHtml="<a class='atticon img' href='".asset("storage/".$name) ."' target='_blank'><img  src='".asset("storage/".$name) ."' /></a>";
        break;
    default:
        $iconHtml="";
        break;
}
return $iconHtml;
}

?>

<?php
$eliminable = ($user->role_id==1) ? true : false ;

?>

<section class='attachList' rel='{{$rel}}' 
uploadto="{{ url('pedidos2/attachpost?catalog=' . $catalog) }}" 
href="{{ $url }}">

<ul>
@foreach ($list as $li)

    @if ($catalog == "pictures") 
    <li class='attachitem'>
    <!--   <img class='atticon' src='{{ asset("storage/".$li->picture) }}' /> -->
    {!! IconOf($li->picture) !!}
        @if (($mode =="edit" || $mode =="") && $eliminable==true)
        <div class='delspace'><a class="delatt" href="{{ url("pedidos2/attachdelete?catalog=".$catalog."&id=".$li->id) }}" title="Eliminar imagen">X</a></div>    
        @endif
    </li>
    
    @elseif ($catalog == "evidence") 
    <li class='attachitem'>
    {!! IconOf($li->file) !!}
        @if (($mode =="edit" || $mode =="") && $eliminable==true)
        <div class='delspace'><a class="delatt" href="{{ url("pedidos2/attachdelete?catalog=".$catalog."&id=".$li->id) }}" title="Eliminar Evidencia">X</a></div>
        @endif    
    </li>
    
    @elseif ($catalog == "shipments") 
    <li class='attachitem'>
     {!! IconOf( isset($li->file) ? $li->file : $li->picture ) !!}
        @if (($mode =="edit" || $mode =="") && $eliminable==true)
        <div class='delspace'><a class="delatt" href="{{ url("pedidos2/attachdelete?catalog=".$catalog."&id=".$li->id) }}" title="Eliminar Evidencia de Embarque">X</a></div>
        @endif
    </li>    
    @endif
    
@endforeach   
</ul>    


@if( $mode == "edit" || $mode == "") 
<div class='attachAddBox'>
    <div><b>Agregar</b></div>
    <div><input type='file' name='attachUpload' class="form-control-file"  accept="capture=camera,image/*,.pdf" /></div>
    <!-- <div><button class='MyAttAdder'>Subir Imagen</button></div>   -->
    <div><div class="attachMonitor"></div></div>
</div>
@endif


<input type='hidden' name='_token' value='{{ csrf_token() }}' />
@foreach ($urlParams as $k=> $v)
	@if (!empty($v))
	<input type='hidden' class='param' name='{{$k}}' value='{{ $v }}' />
	@endif
@endforeach

</section>

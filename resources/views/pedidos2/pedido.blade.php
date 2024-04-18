<?php
use App\Pedidos2;

$hayEntrega=false;
    foreach($pictures as $pic){
        if($pic->event=="entregar"){$hayEntrega=true;break;}
    }


?>
@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Administrar Pedidos')])

@section('content')
<?php
$statuses = Pedidos2::StatusesCat();

?>

<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/pedido.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/attachlist.css?x='.rand(0,999)) }}" />

<main class="content">




    <div class="card">

    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Pedido {{ $pedido->invoice }}</h4>
         </div>
    </div>

<p>&nbsp;</p>

    <form action="{{ url('pedidos2/guardar/'.$pedido->id) }}">

        <fieldset class='MainInfo'>
        <div class='FormRow'><label>Folio</label><input type="text" name="invoice_number" value="{{$pedido->invoice_number}}" /></div>
        <div class='FormRow'><label># Factura</label><input type="text" name="invoice" value="{{$pedido->invoice}}" /></div>
        <div class='FormRow'><label>Cliente</label><input type="text" name="client" value="{{$pedido->client}}" /></div>
        <div class='FormRow'><label></label><span><input type="submit" name="sb" value="Guardar" /> <input type="button" name="cn" value="Cancelar" onclick="EsconderMainInfo()" /></span></div>
        </fieldset>

        

        <fieldset class='MiniInfo'>
            <div><label>Folio</label><span>{{$pedido->invoice_number}}</span></div>
            <div><label># Factura</label><span>{{$pedido->invoice}}</span></div>
            <div><label>Cliente</label><span>{{$pedido->client}}</span></div>
            <div class="block"><a class="powerLink" onclick="MostrarMainInfo()">Cambiar datos principales</a></div>
        </fieldset>

        
    </form>

    
    <div class="BigEstatus E{{ $pedido->status_id }}"><span >{{$pedido->status_name}}</span></div>
    <div class="center">Último cambio: {{$pedido->updated_at}}</div>

    <div class="Cuerpo">
        
        

        @if ($pedido->status_id != 9)
        <h4 class="Fila card-title">Actualizar</h4>
        <p>Mantén presionada la acción que quieres realizar.</p>
        @endif


        <div class="Eleccion">
        @if ($pedido->status_id == 1)
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=recibido') }}">Recibido por embarques</a>
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=ordenf') }}">Orden fabricación</a>
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=fabricado') }}">Fabricado</a>
        @elseif ($pedido->status_id == 2)
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=ordenf') }}">Orden fabricación</a>
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=fabricado') }}">Fabricado</a>
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=enpuerta') }}">En Puerta</a>
        @elseif ($pedido->status_id == 3)
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=fabricado') }}">Fabricado</a>
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=enpuerta') }}">En Puerta</a>
        @elseif ($pedido->status_id == 4)

        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=enpuerta') }}">En Puerta</a>
        @elseif ($pedido->status_id == 5)
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=entregar') }}">Entregado</a>
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=devolucion') }}">Devolución</a>   
        @elseif ($pedido->status_id == 6)
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=devolucion') }}">Devolución</a>
        <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=refacturar') }}">Refacturación</a>      
        @elseif ($pedido->status_id == 7)

        @elseif ($pedido->status_id == 8)
 
        @endif

        </div>



        <div id="AccionSection">


        </div>



        <div>
            <a href="{{  url('pedidos2/historial') }}">Historial</a>
        </div>

    </div>



</div>


<div class="card Fila">
    <center> <a href="{{ url('pedidos2') }}">&laquo; Regresar</a> </center>

</div>



<div class="card">
    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Procesos</h4>
         </div>
    </div>

    @foreach ($shipments as $ship)
    <section class='Proceso'>
        <h3>Shipment #{{ $ship->id }}</h3>

        <section class='attachList' rel='ship{{ $ship->id }}' 
        uploadto="{{ url('pedidos2/attachpost?catalog=pictures&shipment_id='.$ship->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=ship'. $ship->id .'&catalog=pictures&shipment_id='.$ship->id) }}"></section> 
 

    </section>
    @endforeach


    @if ($hayEntrega == true)
    <section class="Proceso">
    <h3>Entrega</h3>
    <section class='attachList' rel='entr' 
        uploadto="{{ url('pedidos2/attachpost?catalog=pictures&event=entregar&order_id='.$pedido->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=entr&catalog=pictures&event=entregar&order_id='.$pedido->id) }}"></section> 
    </section>
    @endif

    <p>&nbsp;</p>
</div>





<div class="card">
    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Parcial</h4>
         </div>
    </div>

    <p>&nbsp;</p>
</div>




@if ($role->name == "Administrador")
    <div class="card">

        <div class='center Fila'>
            <a class="cancelar" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=cancelar') }}">Cancelar</a>
        </div>

    </div>
@endif

</main>

{{ $id }}

<?php var_dump($pedido); ?>

@endsection

@push('js')
<!-- <script type="text/javascript" src="{{asset('js/jquery.mobile-1.4.5.js')}}"></script>-->
<script type="text/javascript" src="{{asset('js/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{asset('js/piedramuda.js')}}"></script>
<script type="text/javascript" src="{{asset('js/attachlist2.js')}}"></script>
<script>
$(document).ready(function(){
    

    timeoutAccion=null;

    ACCIONHTML="";

    let isMobile = isMobileOrTablet();
if(isMobile){
    SetEleccionAccionListenerMobile();
}else{
    SetEleccionAccionListener();
}



$("body").on('touchstart',".Eleccion .Accion", function(){
    console.log("touchstart");
});


$(".Eleccion .Accion").click(function(e){
    e.preventDefault();
});



$(".attachList").each(function(){
    console.log($(this).attr("rel"));
    AttachList($(this).attr("rel"));
});


});


function SetEleccionAccionListener(){
    $("body").on('mousedown',".Eleccion .Accion" ,function() {
    console.log("mt");
    $(".Eleccion .Accion").removeClass("activo");
    $(this).addClass("activo");
    AccionPresionado(this);
    timeoutAccion = setTimeout(AccionPresionadoLlena, 600);

}).on('mouseup mouseleave', ".Eleccion .Accion" , function() {
    $(".Eleccion .Accion").removeClass("activo");
    if(timeoutAccion){
        clearTimeout(timeoutAccion);        
    } 
});
}

function SetEleccionAccionListenerMobile(){
    let botones = document.getElementsByClassName("Accion");
    for(b in botones){

        const boton = botones[b];
        if(typeof(boton.addEventListener)=="undefined"){continue;}

        boton.addEventListener('touchstart',(e)=>{
            e.preventDefault();
            e.stopPropagation();
            AccionPresionado(e.target);
            timeoutAccion = setTimeout(AccionPresionadoLlena, 600, boton);
            $(".Eleccion .Accion").removeClass("activo").removeClass("completo");
          //  $(".Eleccion .Accion").removeClass("activo");
            $(e.target).addClass("activo");
        });
        boton.addEventListener('touchend',(e)=>{
            e.preventDefault();
            e.stopPropagation();
            
            $(".Eleccion .Accion").not(".completo").removeClass("activo");
                if(timeoutAccion){
                 clearTimeout(timeoutAccion);        
                } 
        });        
    }

}





 function MostrarMainInfo(){
    $(".MainInfo").slideDown();
    $(".MiniInfo").hide();
 }
 function EsconderMainInfo(){
    $(".MainInfo").slideUp();
    $(".MiniInfo").show();
 }


 function AccionPresionado(ob){
   let href = $(ob).attr("href");
   console.log(ob);

   $.ajax({
    url:href,
    type:"get",
    error:function(err){alert(err.statusText);},
    success:function(h){
       ACCIONHTML=h;
    }
   });

 }


 function AccionPresionadoLlena(ob){
    if(typeof(ob)=="undefined"){ob=null;}
    $(ob).addClass("completo");
    $("#AccionSection").html(ACCIONHTML);
    FormaAccionSet();
 }
 function FormaAccionSet(){

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
        dataType:"json",
        success:function(json){
            if(json.status==1){
                window.location.reload();
            }
            else if(json.status == 2){
                AccionPaso(json.url); 
            }
        }
    });


    $(".setto").click(function(e){
        e.preventDefault();

        let rel = $(this).attr("rel");
        let val = $(this).attr("val");
        $(this).closest("form").find("[name='"+rel+"']").val(val);
        $(this).closest("form").submit();
    });

    $("#AccionSection .attachList").each(function(){
    AttachList($(this).attr("rel"));
    });

}


function AccionPaso(href){

   $.ajax({
    url:href,
    type:"get",
    error:function(err){alert(err.statusText);},
    success:function(h){
        $("#AccionSection").html(h);
        FormaAccionSet();
        }
   });

 }

</script>    
@endpush
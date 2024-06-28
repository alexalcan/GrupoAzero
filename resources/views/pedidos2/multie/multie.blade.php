<?php
use App\Pedidos2;
use App\Libraries\Tools;

$estatuses =[2=>"Recibido por embarques",3=>"En fabricación",4=>"Fabricado"];

?>
@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Administrar Pedidos')])

@section('content')
<?php
$statuses = Pedidos2::StatusesCat();

?>

<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/pedido.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/piedramuda.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/multie.css?x='.rand(0,999)) }}" />

<main class="content">





    <div class="card Fila">

        <center> <a class="regresar" href="{{ url('pedidos2') }}">&laquo; Regresar</a> </center>
    </div>


    <div class="card">

        <div class="card-header card-header-primary">
            <div class="Fila">
                <h4 class="card-title">Asignar estatus multiple</h4>
            </div>
        </div>

        <p>&nbsp;</p>
        
        <form id="festatus" action="{{url('pedidos2/multie')}}" method="get">
        <p class="Fila center">

        <select name="estatus" class="form-control">
            <option value=""> -Elija uno- </option>
            @foreach ($estatuses as $k => $v)
            <option value="{{$k}}" {{ ($k==$estatus) ? "selected" : "" }}>{{$v}}</option>
            @endforeach

        </select>
        
        
        </p>
        </form>

        <div class="Cuerpo">
        <p>Escriba un shipment en el campo de texto. Aparecerá uno o más pedidos en la lista. </p>
        <p>Puede usar la tecla de <b>Flecha Abajo</b> o la tecla de <b>Flecha Arriba</b> para recorrer varios resultados.</p>
        <p>Use la tecla <b>ENTER</b> para elegir el pedido para cambiar al estatus elegido. </p>

        <section class="SearchZone" >
            <div class="SearchDiv">
                <div class="Filita">
                <input type="text" class="" name="shipment" size="14" maxlength="16" href="{{ url('pedidos2/multie_lista') }}" />
                <span class="buscar"></span>
                </div>
            </div>



            
            <td valign="top">

            <div id="ShipsListaDiv"></div>


        </section>

    </div>


    </div>    

</main>


@endsection

@push('js')
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('jqueryui/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/piedramuda.js') }}"></script> 

<script type="text/javascript">
$(document).ready(function(){

    $("[name='estatus']").change(function(){
        $("#festatus").submit();
    });


    $("[name='shipment']").change(function(){
    let href = $(this).attr("href");
    let val = $(this).val();
    let ide = $("[name='estatus']").val();

        $.ajax({
            url:href,
            data:{term:val, estatus:ide},
            error:function(err){alert(err.statusText);},
            success:function(h){
                $("#ShipsListaDiv").html(h);
                ActivaShipments();
            }
        });

    });


    $("body").on("click", ".Pedido", function(){
        Enfoca(this);
        setTimeout(AbrirConfirmacion, 100);
        
    });


    $("body").on("keyup", function(event){
        console.log(event.keyCode);
        if(event.keyCode == 38){
            Sube();
        }

        if(event.keyCode == 40){
            Baja();
        }

        if(event.keyCode == 13){
            Enter();
        }

    });

    let valEstatus = $("[name='estatus']").val();
    if(valEstatus == ""){
        $(".Cuerpo").hide();
    }


    $("[name='shipment']").change();

});



function Enfoca(ob){
    $(".Pedido").removeClass("focus");
    $(ob).focus();
    $(ob).addClass("focus");
}


function ActivaShipments(){
   // $("#ShipsListaDiv .Pedido").eq(0).focus();
    //$("#ShipsListaDiv .Pedido").eq(0).addClass("focus");
    Enfoca($("#ShipsListaDiv .Pedido").eq(0));
    $("[name='shipment']").blur();
}

function Sube(){
    let currEq = $(".ShipsLista .Pedido.focus").index();

    let nextEq = (currEq  > 0 ) ? currEq - 1 : 0;

    //$(".ShipsLista .Pedido.focus").removeClass("focus");
    //$(".ShipsLista .Pedido").eq(nextEq).addClass("focus").focus();
    Enfoca($(".ShipsLista .Pedido").eq(nextEq));

    let alto = $(".ShipsLista .Pedido").eq(nextEq).height();
    let scrt = (alto + 34) * nextEq; 
    console.log(scrt);
   $(".ShipsLista").scrollTop(scrt);
}

function Baja(){
    let currEq = $(".ShipsLista .Pedido.focus").index();
    //console.log(currEq);
    let max =  $(".Pedido").length-1;
    let nextEq = (currEq  < max ) ? currEq + 1 : max;
    //$(".ShipsLista .Pedido.focus").removeClass("focus");
    //$(".ShipsLista .Pedido").eq(nextEq).addClass("focus");
    Enfoca($(".ShipsLista .Pedido").eq(nextEq));
    
    let alto = $(".ShipsLista .Pedido").eq(nextEq).height();
    let scrt = (alto + 34) * nextEq; 
    console.log(scrt);
   $(".ShipsLista").scrollTop(scrt);
}

function Enter(){
let hay = $(".ShipsLista .Pedido.focus").length;
let busIsFocus = $("[name='shipment']").is(":focus");

    if(hay > 0 && !busIsFocus ){
        setTimeout(AbrirConfirmacion, 500);
    }
}

function AbrirConfirmacion(){
    let focused = $(".ShipsLista .Pedido.focus");
    let href = $(focused).attr("del");
    //let cont = $(focused).find(".Cont").html();
    let strEstatus = $("[name='estatus']").find(":selected").text();
    let idEstatus = $("[name='estatus']").val();
    //let conth = "<div class='Pedido'><div class='Cont'>" +cont + "</div></div>" + "<p>Confirma que este pedido cambiará al estatus '"+strEstatus+"'</p>";
    let txt =  "Confirma que este pedido cambiará al estatus '"+strEstatus+"'";
    //MiModal.content(conth);
    //MiModal.show();
    if(confirm(txt)){
        $.ajax({
            url:href,
            datatype:"json",
            data:{ids:idEstatus},
            success:function(json){
                FocusBuscar();
                $("[name='shipment']").change();
            }
        });
        
    }
}

function FocusBuscar(){
    $("[name='shipment']").focus();
}

</script>



@endpush
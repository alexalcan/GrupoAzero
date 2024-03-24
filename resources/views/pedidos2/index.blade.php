<?php
use App\Pedidos2;
?>
@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Administrar Pedidos')])

@section('content')
<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css') }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/index.css') }}" />
<link rel="stylesheet" href="{{ asset('js/drp/daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('css/paginacion.css') }}" />
<link rel="stylesheet" href="{{ asset('css/piedramuda.css') }}" />
<main class="content">
    <div class="card">
    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Pedidos</h4>
            <div class="card-category">Versión 2</div>
        </div>
    </div>



    <form id="fbuscar" action="{{ url('pedidos2/lista') }}" method="get" enctype="multipart/form-data">
        <input type="hidden" name="p" value="1" />
        <section class="formaBuscar">
            <div class="terminoBox">
                <input type="text" name="termino"  maxlength="90" />
                <input type="button" id="buscarBoton" value="Buscar" />
            </div>
            <div class="fechasBox">
                <label for="fechas"><span id="MuestraFecha"></span></label>
                <?php
                $desde = new DateTime();
                $hoyf= $desde->format("Y-m-d");
                $desde->modify("-7 month");
                $desdef = $desde->format("Y-m-d");
                ?>
                <div class="divFechas"><input type="text" id="fechas" name="fechas" value="{{ $desdef}} - {{ $hoyf }}" /></div>
                <div class="fechasNotas"><small>Selecciona PRIMERO la fecha inicial y DESPUES la fecha final</small></div>
            </div>
            
            <div class="Fila center" id="MuestraAvanzada"><a class='toggleLink' tabindex="3">Búsqueda Avanzada</a></div>

            <?php
        $statuses = Pedidos2::StatusesCat();
        $events = Pedidos2::EventsCat();
            ?>
            
            <aside class="Avanzados">
                <div class="AvanzadosSet">
                <fieldset>
                    <legend>Status</legend>
                    @foreach ($statuses as $k=>$v)
                    <div class="checkpair"><input type="checkbox" name="st[]" value="{{ $k }}" id="st_{{ $v }}"> <label for="st_{{ $v }}">{{ $v }}</label></div>
                    @endforeach 

                </fieldset>
                <fieldset>
                    <legend>Subprocesos</legend>
                    @foreach ($events as $k=>$v)
                    <div class="checkpair"><input type="checkbox" name="sp[]" value="{{ $k }}" id="sp_{{ $v }}"> <label for="sp_{{ $v }}">{{ $v }}</label></div>
                    @endforeach
                </fieldset>
                <fieldset>
                    <legend>Origen</legend>
                    <div class="checkpair"><input type="checkbox" name="or[]" value="C" id="or_C"> <label for="or_C">Cotización</label></div>
                    <div class="checkpair"><input type="checkbox" name="or[]" value="F" id="or_F"> <label for="or_F">Factura</label></div>
                    <div class="checkpair"><input type="checkbox" name="or[]" value="I" id="or_I"> <label for="or_I">Req Interna</label></div>
                </fieldset>
                <fieldset>
                    <legend>Sucursal</legend>
                    <div class="checkpair"><input type="checkbox" name="suc[]" value="San Pablo" id="suc_S"> <label for="suc_S">San Pablo</label></div>
                    <div class="checkpair"><input type="checkbox" name="suc[]" value="La Noria" id="suc_N"> <label for="suc_N">La Noria</label></div>
                </fieldset>
                </div>
                <div class="Fila center"><input type="button" value="Buscar" onclick="GetLista()" /> </div>
            </aside>
            
            
            <div class="Fila center" id="MuestraSimple"><a class='toggleLink' tabindex="4">Búsqueda Simple</a></div>
        
        </section>

        </form>




    </div>


    <div class="container-fluid">
        <div class="right Fila"><button>Crear Nuevo Pedido</button></div>    
    </div>


        <section id="Lista">



        </section>

        <input type="hidden" name="listaUrl" value="{{ url('pedidos2/lista') }}" />
</main>

@endsection

@push('js')
{{-- Comment --}}
<script type="text/javascript" src="{{ asset('js/drp/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/drp/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/piedramuda.js') }}"></script>

<script>
$(document).ready(function(){

    $('input[name="fechas"]').daterangepicker({
    timePicker: false,
    minDate: new Date("2021-10-11"),
    maxDate: new Date(),
    maxSpans:{
        "years":4
    },
    linkedCalendars: false,
    showDropdowns:true,

    locale: {
      format: 'YYYY-MM-DD',
      "weekLabel": "W",
        "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
        ],
        "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
        ],
    }
    },FormatearFecha);

    var start = moment().subtract(7, 'months');
    var end = moment();

    FormatearFecha(start,end);

    
    $("#MuestraAvanzada a").click(function(e){
        e.preventDefault();
    MuestraAvanzada();
    });
    $("#MuestraSimple a").click(function(e){
        e.preventDefault();
    MuestraSimple();
    });
    MuestraSimple();

    $("#buscarBoton").click(function(e){
        e.preventDefault();
        GetLista();
    });

    $("body").on("click", ".paginacion a", function(e){
        e.preventDefault();
        let rel=$(this).attr("rel");

        GetLista(parseInt(rel));
    });

    $("body").on("click", ".masinfo", function(e){
        e.preventDefault();
        let href=$(this).attr("href");

        MiModal.showBg();

        $.ajax({
            url:href,
            error:function(err){alert(err.statusText);},
            type:"get",
            success:function(h){            
            MiModal.content(h);
            MiModal.width ="75vw";
            MiModal.show();
            }
        });
    });

    GetLista();

});

function MuestraAvanzada(){
    $(".Avanzados").slideDown();
    $("#MuestraSimple").show();
    $("#MuestraAvanzada").hide();
}
function MuestraSimple(){
    $(".Avanzados").slideUp();
    $("#MuestraSimple").hide();
    $("#MuestraAvanzada").show();
}



function GetLista(p){
    if(typeof(p)=="undefined"){p=1;}

    $("#fbuscar [name='p']").val(p);

    $("#Lista").html("<p>Cargando...</p>");

    $("#fbuscar").ajaxSubmit({
        error:function(err){alert(err.statusText);},
        success:function(h){
        $("#Lista").html(h);
        }
    });

}

function GetListaPre(p){
    if(typeof(p)=="undefined"){p=0;}
    
    let href = $("[name='listaUrl']").val();
    let datos = GeneraFiltros();
    if(p>0){
        datos["p"]=p;
    }
    

    $("#Lista").html("<p>Cargando...</p>");
    $.ajax({
        url:href,
        method:"get",
        data:datos,
        error:function(err){alert(err.statusText);},
        success:function(h){
        $("#Lista").html(h);
        }
    });
}

function GeneraFiltros(){
    let terminoVal = $("[name='termino']").val();

    let fechas = $("[name='fechas']").val();
    let fechasArr = fechas.split(' - ');
    if(fechasArr.length < 2){alert("fechas Arr");}

    let desdeVal = fechasArr[0];
    let hastaVal = fechasArr[1];

console.log(desdeVal);
console.log(hastaVal);


return {termino:terminoVal, desde:desdeVal, hasta:hastaVal};
}


function FormatearFecha(start,end,label){
    const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    };
    let v = start._d.toLocaleDateString("es-MX",options)+" - "+end._d.toLocaleDateString("es-MX",options);
    $("#MuestraFecha").text(v);
}

</script>

@endpush
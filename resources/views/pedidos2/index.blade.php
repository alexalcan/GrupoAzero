@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Administrar Pedidos')])

@section('content')
<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css') }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/index.css') }}" />
<link rel="stylesheet" href="{{ asset('js/drp/daterangepicker.css') }}" />
<main class="content">
    <div class="card">
    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Pedidos</h4>
            <div class="card-category">Versión 2</div>
        </div>
    </div>



    <form method="get" enctype="multipart/form-data">
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

            <aside class="Avanzados">
                <fieldset>
                    <legend>Status</legend>
                    <div><input type="checkbox" name="status[]" value="G"><label>Pedido generado</label></div>
                    <div><input type="checkbox" name="status[]" value="R"><label>Recibido por embarque</label></div>
                    <div><input type="checkbox" name="status[]" value="R"><label>En fabricación</label></div>
                    <div><input type="checkbox" name="status[]" value="R"><label>Fabricado</label></div>
                </fieldset>
                <fieldset>
                    <legend>Subprocesos</legend>
                    <div><input type="checkbox" name="subproceso[]" value="G"><label>Orden de Fabricación</label></div>
                    <div><input type="checkbox" name="subproceso[]" value="R"><label>OC/Orden Interna</label></div>
                </fieldset>
                <fieldset>
                    <legend>Origen</legend>
                    <div><input type="checkbox" name="origen[]" value="G"><label>Cotización</label></div>
                    <div><input type="checkbox" name="origen[]" value="R"><label>Factura</label></div>
                    <div><input type="checkbox" name="origen[]" value="R"><label>Req Interna</label></div>
                </fieldset>
                <fieldset>
                    <legend>Sucursal</legend>
                    <div><input type="checkbox" name="sucursal[]" value="G"><label>San Pablo</label></div>
                    <div><input type="checkbox" name="sucursal[]" value="R"><label>La Noria</label></div>
                </fieldset>

            </aside>
            
            <div class="Fila center" id="MuestraSimple"><a class='toggleLink' tabindex="4">Búsqueda Simple</a></div>
        
        </section>


        </form>

        <div class="container-fluid">
        <div class="right Fila"><button>Crear Nuevo Pedido</button></div>    
    </div>

    </div>



        <section id="Lista">



        </section>

        <input type="hidden" name="listaUrl" value="{{ url('pedidos2/lista') }}" />
</main>

@endsection

@push('js')
{{-- Comment --}}
<script type="text/javascript" src="{{ asset('js/drp/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/drp/daterangepicker.js') }}"></script>
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


function GetLista(){
    let href = $("[name='listaUrl']").val();
    let datos = GeneraFiltros();

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
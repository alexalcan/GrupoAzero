<?php
use App\Pedidos2;
use App\Http\Controllers\Pedidos2Controller;
//var_dump($statuses->toArray());
?>
@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Administrar Pedidos')])

@section('content')
<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css') }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/index.css').'?x='.rand(0,999) }}" />
<link rel="stylesheet" href="{{ asset('js/drp/daterangepicker.css') }}" />
<link rel="stylesheet" href="{{ asset('css/paginacion.css') }}" />
<link rel="stylesheet" href="{{ asset('css/piedramuda.css') }}" />
<link rel="stylesheet" href="{{ asset('jqueryui/jquery-ui.min.css') }}" />

<main class="content">
    <div class="card">
    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Pedidos</h4>
            <div class="card-category">Versión 2</div>
        </div>
    </div>



    <form id="fbuscar" action="{{ url('pedidos2/lista') }}" method="get" enctype="multipart/form-data">
        <input type="hidden" name="p" value="{{ $pag }}" />
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
            
            <div class="Fila center" id="MuestraAvanzada">
                <span class="filtrosIcon">!</span> 
                <a class='toggleLink' tabindex="3">Búsqueda Avanzada</a>
            </div>


            <div>&nbsp;</div>


            <?php
        $statuses = Pedidos2::StatusesCat();
        $events = Pedidos2::EventsCat();
            ?>
            
            <aside class="Avanzados">
                <div class="AvanzadosSet">
                <fieldset>
                    <legend>Status</legend>
               
                    @foreach ($statuses as $k=>$v)
                        @if ( !in_array($k, [3,4,9] ))
                        <div class="checkpair"><input type="checkbox" name="st[]" value="{{ $k }}" id="st_{{ $v }}"> <label for="st_{{ $v }}">{{ $v }}</label></div>

                        @endif
                    @endforeach 

                </fieldset>
                <fieldset>
                    <legend>Subprocesos</legend>
                    @foreach ($events as $k=>$v)
                        @if ($k == "refacturar")
                            @continue
                        @endif 
                    <div class="checkpair parent" rel="{{ $k }}"><input type="checkbox" name="sp[]" value="{{ $k }}" id="sp_{{ $v }}"> <label for="sp_{{ $v }}">{{ ($k=="ordenc") ? "Requisición": $v }}</label></div>
                            @if ($k =="ordenc") 
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_1" id="spsub_{{$k}}_1"> <label for="spsub_{{$k}}_1">Elaborada</label></div>
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_5" id="spsub_{{$k}}_5"> <label for="spsub_{{$k}}_5">En puerta</label></div>                            
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_6" id="spsub_{{$k}}_6"> <label for="spsub_{{$k}}_6">Entregada</label></div>   
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_7" id="spsub_{{$k}}_7"> <label for="spsub_{{$k}}_7">Cancelada</label></div>   
                            
                            @elseif ($k =="ordenf") 
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_1" id="spsub_{{$k}}_1"> <label for="spsub_{{$k}}_1">Elaborada</label></div>
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_3" id="spsub_{{$k}}_3"> <label for="spsub_{{$k}}_3">En fabricación</label></div>
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_4" id="spsub_{{$k}}_4"> <label for="spsub_{{$k}}_4">Fabricada</label></div>
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_7" id="spsub_{{$k}}_7"> <label for="spsub_{{$k}}_7">Cancelado</label></div>
                            
                            @elseif ($k =="parcial") 
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_4" id="spsub_{{$k}}_4"> <label for="spsub_{{$k}}_4">Elaborada</label></div>
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_5" id="spsub_{{$k}}_5"> <label for="spsub_{{$k}}_5">En puerta</label></div>
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_6" id="spsub_{{$k}}_6"> <label for="spsub_{{$k}}_6">Entregada</label></div>
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_7" id="spsub_{{$k}}_7"> <label for="spsub_{{$k}}_7">Cancelada</label></div>

                            @elseif ($k =="sm") 
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_4" id="spsub_{{$k}}_4"> <label for="spsub_{{$k}}_4">Elaborada</label></div>
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_5" id="spsub_{{$k}}_5"> <label for="spsub_{{$k}}_5">En puerta</label></div>
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_6" id="spsub_{{$k}}_6"> <label for="spsub_{{$k}}_6">Entregada</label></div>
                            <div class="checkpair sub" parent="{{$k}}"><input type="checkbox" name="spsub[]" value="{{$k}}_7" id="spsub_{{$k}}_7"> <label for="spsub_{{$k}}_7">Cancelada</label></div>
                            @endif

                    @endforeach
                </fieldset>
                <fieldset>
                    <legend>Origen</legend>
                    <div class="checkpair parent" rel="C"><input type="checkbox" name="or[]" value="C" id="or_C"> <label for="or_C">Cotización</label></div>
                        <div class="checkpair sub" parent="C"><input type="checkbox" name="orsub[]" value="C_0" id="orsub_C_0"> <label for="orsub_C_0">Sin Factura</label></div>
                        <div class="checkpair sub" parent="C"><input type="checkbox" name="orsub[]" value="C_1" id="orsub_C_1"> <label for="orsub_C_1">Con Factura</label></div>
                    
                    <div class="checkpair"><input type="checkbox" name="or[]" value="F" id="or_F"> <label for="or_F">Factura</label></div>


                    <div class="checkpair" ><input type="checkbox" name="or[]" value="R" id="or_R"> <label for="or_R">Requisición Stock</label></div>
                


                    <legend>Recolección</legend>
                    <div class="checkpair"><input type="checkbox" name="rec[]" value="1" id="rec_1"> <label for="rec_1">Chofer Entrega</label></div>
                    <div class="checkpair"><input type="checkbox" name="rec[]" value="2" id="rec_2"> <label for="rec_2">Cliente recoge</label></div>

                </fieldset>
                <fieldset>
                    <legend>Sucursal</legend>
                    <div class="checkpair"><input type="checkbox" name="suc[]" value="San Pablo" id="suc_S"> <label for="suc_S">San Pablo</label></div>
                    <div class="checkpair"><input type="checkbox" name="suc[]" value="La Noria" id="suc_N"> <label for="suc_N">La Noria</label></div>
                </fieldset>
                </div>
                <div class="Fila center"><input type="button" class="form-control btnGrande" value="Buscar" onclick="GetLista()" /> </div>
            </aside>
            
            <div>&nbsp;</div>
            
            <div class="Fila center" id="MuestraSimple"><a class='toggleLink' tabindex="4">Búsqueda Simple</a></div>
        
        </section>

        </form>




    </div>

    @if ($user->role_id == 1 || (in_array($user->department_id, [3, 4 , 8]) ) )
    <div class="container-fluid">
        <div class="BotsPrincipales">
        
        <button class="nuevo" href="{{ url('pedidos2/multie') }}">Cambio de Estatus Masivo </button>   


        <button class="nuevo" href="{{ url('pedidos2/nuevo') }}">Crear Nuevo Pedido</button>
    
        </div>    
    </div>
    @endif

        <section id="Lista">



        </section>

        <input type="hidden" name="listaUrl" value="{{ url('pedidos2/lista') }}" />
</main>

<input type="hidden" name="querystring" value="{{ json_encode($queryString) }}" />

<?php 
//var_dump($request); 
?>


@endsection

@push('js')
{{-- Comment --}}

<script type="text/javascript" src="{{ asset('js/drp/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/drp/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('jqueryui/jquery-ui.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('js/piedramuda.js') }}"></script> 


<script type="text/javascript" >
    
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


    //Buscar
    $("#buscarBoton").click(function(e){
        e.preventDefault();
        GetLista();
    });

    //paginacion
    $("body").on("click", ".paginacion a", function(e){
        e.preventDefault();
        let rel=$(this).attr("rel");

        GetLista(parseInt(rel));
    });

    //Masinfo
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

    //NUEVO
    $(".nuevo").click(function(){
        window.location.href = $(this).attr("href");
    });


    
    $("input[name='termino']").on("keyup",function(e){
        if(e.keyCode==13){$("#buscarBoton").click();}
    });



    
    //ICONS
    $("body").on("click",".iconSet a",function(e){
        e.preventDefault();
        e.stopPropagation();
        let href = $(this).attr("href");      
        
        $.ajax({
            url:href,
            success:function(h){
                MiModal.content(h);
                MiModal.show();     
            }
        });          

    });


    Querystring();


   GetLista();

});



function Querystring(){
    let qs = $("[name='querystring']").val();
    var qsob = JSON.parse(qs);
    if(qsob == null){
        return;
    }
    console.log(qsob);

    if(typeof(qsob.termino) != "undefined" && qsob.termino != null ){
     $("[name='termino']").val(qsob.termino);  

    }
    //console.log(num);
    if(typeof(qsob.st) != "undefined"){
        for(i in qsob.st){
            $("[name='st[]'][value='"+qsob.st[i]+"']").prop("checked",true);  
        }    

    }
    if(typeof(qsob.fechas) != "undefined" && qsob.fechas != null){
     $("[name='fechas']").val(qsob.fechas);   
     var fechasArr = qsob.fechas.split(' - ');
     let ini = new Date(fechasArr[0]+"T00:00:00");
     let fin = new Date(fechasArr[1]+"T23:59:59");
     FormatearFechaDate(ini, fin,"qs");

    }
    //console.log(num);
    if(typeof(qsob.sp) != "undefined"){
        for(i in qsob.sp){
            $("[name='sp[]'][value='"+qsob.sp[i]+"']").prop("checked",true);  
        } 
 
    }
    if(typeof(qsob.or) != "undefined"){
        for(i in qsob.or){
            $("[name='or[]'][value='"+qsob.or[i]+"']").prop("checked",true);  
        }     
    }
    if(typeof(qsob.suc) != "undefined"){
        for(i in qsob.suc){
            $("[name='suc[]'][value='"+qsob.suc[i]+"']").prop("checked",true);  
        }   
    }


}

function CuentaFiltros(){
    var num=0;
    num += ($("[name='termino']").val().length > 0 )? 1 : 0 ;
    num += ($("[name='st[]']:checked").length > 0 )? 1 : 0 ;
    num += ($("[name='sp[]']:checked").length > 0 )? 1 : 0 ;
    num += ($("[name='or[]']:checked").length > 0 )? 1 : 0 ;
    num += ($("[name='suc[]']:checked").length > 0 )? 1 : 0 ;
    let b = (num>0)?true:false;
    MuestraIconFiltros(b);
    console.log(num);
}


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
    if(typeof(p)=="undefined"){p=0;}
console.log("GetLista");
    if(p != 0){
        $("#fbuscar [name='p']").val(p);
    }


    $("#Lista").html("<p>Cargando...</p>");

    MuestraSimple();

    $("#fbuscar").ajaxSubmit({
        error:function(err){alert(err.statusText);},
        success:function(h){
        $("#Lista").html(h);

        LimpiaFiltros();
        $("#fbuscar [name='p']").val(1);
        $("#Lista").tooltip();

        }
    });

    
    //Subfiltros
    $(".checkpair.sub").hide();
    $(".checkpair.parent").click(function(){
        let ch = $(this).find(":checkbox").is(":checked");
        let rel = $(this).attr("rel");
        if(ch){
            $(".checkpair.sub[parent='"+rel+"']").show();
        }else{
            $(".checkpair.sub[parent='"+rel+"']").hide();
        }       
    });


    $("#Lista").tooltip();

  //  CuentaFiltros();
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
function FormatearFechaDate(start,end,label){
    const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    };
    let v = start.toLocaleDateString("es-MX",options)+" - "+end.toLocaleDateString("es-MX",options);
    $("#MuestraFecha").text(v);
}

function MuestraIconFiltros(es){
    if(es){
        $(".filtrosIcon").css("display","inline-block");
    }else{
        $(".filtrosIcon").css("display","none");
    }
}

function LimpiaFiltros(){
    document.getElementById("fbuscar").reset();  
}

</script>

@endpush
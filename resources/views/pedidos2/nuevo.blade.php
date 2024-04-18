@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Crear Pedido')])

@section('content')


<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css') }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/pedido.css?x='.rand(0,999)) }}" />
<input type="hidden" name="url_previous" value="{{ url()->previous()  }}"/>


<main class="content" >
    <section class="card">
    <div class="card-header card-header-primary">
        <div class="Fila">
            <h4 class="card-title">Pedido Nuevo</h4>
         </div>
    </div>

<p>&nbsp;</p>

    <form action="{{ url('pedidos2/crear') }}" id="FNuevo" class="Cuerpo" method="post">
        @csrf
    <fieldset>
            <p class="center">Iniciar con </p>
            <div class="Eleccion">
                <button class="Tipo" rel="F">Factura</button>
                <button class="Tipo" rel="C">Cotización</button>
                <button class="Tipo" rel="R">Requerimiento Interno</button>
            </div>
        </fieldset>
        <input type="hidden" name="origin" value=""/>
        <div>&nbsp;</div>

        <fieldset class="Tiposet or">
            <dl>
            <dt><label rel="code" F="Folio Factura *" C="Num Cotizacion *" R="Num Requerimiento *">Folio/Codigo</label></dt> <dd><input type="text" name="code" class="form-control" /></dd>

            <dt><label rel="archivo" F="Scan Factura" C="Archivo Cotizacion" R="Archivo Requerimiento">Archivo</label> </dt> <dd><input type="file" name="archivo"  class="form-control" /></dd>


            <dt><label>Numero de Folio *</label></dt> <dd> <input type="text" name="invoice" class="form-control" /></dd>

            <dt><label>Clave de Cliente *</label></dt> <dd> <input type="text" name="client"  class="form-control"/></dd>

            <dt><label>Note</label></dt> <dd> <textarea  name="nota" class="form-control" ></textarea></dd>

            <dt></dt> <dd><input type="submit" name="sb" value="Guardar"><span id="preGuardar">Indique todos los datos obligatorios * para guardar</span></dd>
        </dl>
        </fieldset>

    
    </form>
</main>

    @endsection

@push('js')
<script type="text/javascript" src="{{ asset('js/jquery.form.js')  }}" ></script>
<script >
$(document).ready(function(){

$(".Eleccion button").click(function(e){
    e.preventDefault();

    $(".Eleccion button").removeClass("activo");
    $(this).addClass("activo");
    let r = $(this).attr("rel");

    ShowTiposet(r);
});

$("body").on("change", ".Tiposet input", function(){
    UnlockContinuar(this);
});



$("#FNuevo").ajaxForm({
    dataType:"json",
    error:function(err){
        alert(err.statuText);
    },
    success:function(json){
        if(json.status==0){
            alert(json.errors);
        }else{
            window.location.href = json.goto;
        }
    }
});





$(".Tiposet.or").hide();

});

function ShowTiposet(r){   

    $(".Tiposet.or").show();
    $("#FNuevo [name='origin']").val(r);
    $("#FNuevo [name='sb']").hide();
    $(".Tiposet label[rel='code']").text($(".Tiposet label[rel='code']").attr(r));
    $(".Tiposet label[rel='archivo']").text($(".Tiposet label[rel='archivo']").attr(r));

}

function UnlockContinuar(ob){
    let cd = $(ob).closest(".Tiposet").find("[name='code']").val();
    let ar = $(ob).closest(".Tiposet").find("[name='archivo']").val();
    let inv = $(ob).closest(".Tiposet").find("[name='invoice']").val();
    let cli = $(ob).closest(".Tiposet").find("[name='client']").val();

    if(cd.length > 2  && inv.length > 2 && cli.length > 2){
        $("#FNuevo [name='sb']").show();
        $("#preGuardar").hide();
    }else{
        $("#FNuevo [name='sb']").hide();
        $("#preGuardar").show();
    }
}
</script>

@endpush
<?php
use App\Pedidos2;
use App\Libraries\Tools;



?>
@extends('layouts.app', ['activePage' => 'orders', 'titlePage' => __('Administrar Pedidos')])

@section('content')
<?php
$statuses = Pedidos2::StatusesCat();

?>

<link rel="stylesheet" href="{{ asset('css/pedidos2/general.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/pedidos2/pedido.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/attachlist.css?x='.rand(0,999)) }}" />
<link rel="stylesheet" href="{{ asset('css/piedramuda.css?x='.rand(0,999)) }}" />

<main class="content">





    <div class="card Fila">

        <center> <a class="regresar" href="{{ url('pedidos2') }}">&laquo; Regresar</a> </center>
    </div>


    <div class="card">

        <div class="card-header card-header-primary">
            <div class="Fila">
                <h4 class="card-title">Pedido {{ !empty($pedido->invoice_number) ? $pedido->invoice_number : $pedido->invoice }}</h4>
            </div>
        </div>

        <div>&nbsp;</div>

    <form action="{{ url('pedidos2/guardar/'.$pedido->id) }}" method="post" enctype="multipart/form-data">
    @csrf
        <fieldset class='MainInfo'>
        <div class='FormRow'>
            <label>Folio Cotización</label>
            <input type="text" class="form-control" name="invoice" value="{{$pedido->invoice}}" />
        </div>
        <div class='FormRow'>
            <label>Archivo Cotización</label>
            <div class="flex-start">
            <input type="file" class="form-control" name="cotizacion"  />  
                @if (!empty($quote->document))
                <span> &nbsp; &nbsp; Actual: 
                &nbsp; <a class="pdf" target="_blank" href="{{ asset('storage/'.$quote->document) }}"></a>
                </span>
                @endif 
                
            </div>
        </div>
        <div class='FormRow'>
            <label># Factura</label>
        <input type="text" class="form-control" name="invoice_number" value="{{$pedido->invoice_number}}" />
        </div>
        <div class='FormRow'>
            <label>Archivo Factura</label>
            
                <div class="flex-start">
                <input type="file" class="form-control" name="factura" />
                
                @if (!empty($purchaseOrder->document))
                <span>  &nbsp; &nbsp; Actual: 
                &nbsp; <a class="pdf" target="_blank" href="{{ asset('storage/'.$purchaseOrder->document) }}"></a>
                </span> 
                @endif
                
                </div>
        </div>
        <div class='FormRow'>
            <label>Cliente</label>
            <input type="text" class="form-control" name="client" value="{{$pedido->client}}" />
        </div>

        <div class='FormRow'>
            <label></label>
            <span>
                <input type="submit" name="sb" class="form-control" value="Guardar" /> 
                <input type="button" name="cn" class="form-control" value="Cancelar" onclick="EsconderMainInfo()" />
            </span></div>
        </fieldset>

        

        <fieldset class='MiniInfo'>


            <div>
                <label># Factura</label><span>{{$pedido->invoice_number}}
                <?php 
                //var_dump($purchaseOrder) 
                ?>
                @if (!empty($purchaseOrder->document))
                &nbsp; <a class="pdf" target="_blank" href="{{ asset('storage/'.$purchaseOrder->document) }}"></a>
                @endif 

            </span>
            </div>

            <div>
                <label>Folio Cotización</label>
                <span>{{$pedido->invoice}} 
                 @if (!empty($quote->document))
                &nbsp; <a class="pdf" target="_blank" href="{{ asset('storage/'.$quote->document) }}"></a>
                @endif 
                </span>
            </div>


            <div><label>Cliente</label><span>{{$pedido->client}}</span></div>

           

        </fieldset>

    <div class="padded flex-float">
        <a class="powerLink modalShow" href="{{  url('pedidos2/historial/'.$pedido->id) }}">Historial</a>  
        @if ($user->role->id == 1)    
            <div class="block"><a class="powerLink" onclick="MostrarMainInfo()">Cambiar datos principales</a></div>
        @endif      
    </div>
        
    </form> 
    

    <div class="BigEstatus E{{ $pedido->status_id }}"><span >{{$pedido->status_name}}</span></div>
    <div class="center">Último cambio: {{ Tools::fechaMedioLargo($pedido->updated_at, true) }}</div>

    <p>&nbsp;</p>
</div>






<div class="card">
    <div class="headersub">
    Progreso
    </div>

    <div class="Cuerpo" id="CuerpoActualizar">       

    <?php                                                                                                                                                                          

    ?>
        <div class="Eleccion">
        @if ($pedido->status_id == 1)

            @if ($user->role_id == 1 || $user->department_id == 4)
            <a class="Accion generico" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=recibido') }}">Recibido por embarques</a>
            @endif

            <a class="Accion enpuerta" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=enpuerta') }}">En Puerta</a>

        <!-- <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=fabricado') }}">Fabricado</a> -->

        @elseif ($pedido->status_id == 2)
        <!-- Recibido por embarques -->

        <a class="Accion generico" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=fabricado') }}">Fabricado</a>
        <a class="Accion enpuerta" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=enpuerta') }}">En Puerta</a> 

        @elseif ($pedido->status_id == 3)
        <!-- En fabricación -->
        <a class="Accion generico" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=fabricado') }}">Fabricado</a>
        <!-- <a class="Accion" href="{{ url('pedidos2/parcial_accion/'.$pedido->id.'?a=enpuerta') }}">En Puerta</a> -->

        @elseif ($pedido->status_id == 4)
        <!-- Fabricado -->
        <a class="Accion enpuerta" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=enpuerta') }}">En Puerta</a>

        @elseif ($pedido->status_id == 5)
        <!-- En Ruta -->
        <a class="Accion generico" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=entregar') }}">Entregado</a>

        @elseif ($pedido->status_id == 6)
        <!-- Entregado -->
        @elseif ($pedido->status_id == 7)

        @elseif ($pedido->status_id == 8)




        @endif

        </div>
    </div>



    @if ( $shipments->isEmpty() == false)
    @foreach ($shipments as $ship)
    <aside class="Proceso">
        <div class="gridThree">
            <span class="a">Paso por Puerta: {{  ($ship->type == 1) ? "Envío" : "Entrega Directa" }}</span>
            <span class="b">
             {{ Tools::fechaMedioLargo($ship->created_at) }}
            </span>
            <span class="last">
                <a class="btn  editapg" href="{{ url('pedidos2/shipment_edit/'.$ship->id) }}">Editar</a>
            </span>
        </div>
    
    <section mode="view" class='attachList' rel='ship_{{ $ship->id }}' 
        uploadto="{{ url('pedidos2/attachpost?catalog=shipments&shipment_id='.$ship->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=ship_'. $ship->id .'&mode=view&catalog=shipments&shipment_id='.$ship->id) }}"></section> 
    </aside>

    @endforeach
    @endif


    @if ($imagenesEntrega->isEmpty()==false)
    <aside class="Proceso">
        <div class="gridThree">
            <span class="a">Entrega</span>
            <span class="b">
            &nbsp;
            </span>
            <span class="last">
                <a class="btn  editapg" href="{{ url('pedidos2/entregar_edit/'.$pedido->id) }}">Editar</a>
            </span>
        </div>

    <section mode="view" class='attachList' rel='entrega_{{ $pedido->id }}' 
        uploadto="{{ url('pedidos2/attachpost?catalog=pictures&event=entregar&order_id='.$pedido->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=ship_'. $pedido->id .'&mode=view&catalog=pictures&event=entregar&order_id='.$pedido->id) }}">
    </section> 
    
    </aside>
    @endif

    
</div>












<div class="card">
    <div class="headersub">
     Sub Procesos
    </div>

    <div class="Eleccion ">

    @if ($user->role_id == 1 ||  in_array($user->departamento_id, [2,4] ) )
        <a class="Candidato" rel="smaterial" href="{{ url('pedidos2/subproceso_nuevo/'.$pedido->id.'?a=smaterial') }}">+ Salida de Material</a>
    @endif

        
        <a class="Candidato" rel="requisicion" href="{{ url('pedidos2/subproceso_nuevo/'.$pedido->id.'?a=requisicion') }}">+ Requisición</a>
    
        <a class="Candidato" rel="ordenf" href="{{ url('pedidos2/subproceso_nuevo/'.$pedido->id.'?a=ordenf') }}">+ Orden de fábrica</a>
    
        <a class="NParcial Candidato subp" href="{{ url('pedidos2/accion/'.$pedido->id.'?a=parcial') }}">+ Parcial</a>
        
        @if ($pedido->status_id == 7) 
        <a class="Candidato" rel="devolucion" href="{{ url('pedidos2/subproceso_nuevo/'.$pedido->id.'?a=devolucion') }}">Devolución</a> 
        <a class="Candidato" rel="refacturacion" href="{{ url('pedidos2/subproceso_nuevo/'.$pedido->id.'?a=refacturacion') }}">Refacturación</a>
        @endif
    
    </div>


    <div id="DevolucionDiv" href="{{ url('pedidos2/devolucion_lista/'.$pedido->id) }}" class="SubProcesoContainer"></div>

    <div id="SmaterialDiv" href="{{ url('pedidos2/smaterial_lista/'.$pedido->id) }}" class="SubProcesoContainer"></div>

    <div id="RequisicionDiv" href="{{ url('pedidos2/requisicion_lista/'.$pedido->id) }}" class="SubProcesoContainer"></div>  

    <div id="OrdenfDiv" href="{{ url('pedidos2/ordenf_lista/'.$pedido->id) }}" class="SubProcesoContainer"></div>

    <div id="ParcialesDiv" href="{{ url('pedidos2/parcial_lista/'.$pedido->id) }}"></div>


    <p>&nbsp;</p>
</div>










@if ( in_array($user->department->id,[2, 3, 5, 7]))

    <div class="card">

        <div class='center Fila'>
            @if ($pedido->status_id != 7)
            <a class="cancelar" title="¿Confirma que desea cancelar este pedido?" 
            href="{{ url('pedidos2/cancelar/'.$pedido->id) }}">Cancelar</a>
            @elseif ($pedido->status_id == 7 && $role->name == "Administrador")
            <a class="cancelar" title="¿Confirma que desea quitar la cancelación de este pedido?" 
            href="{{ url('pedidos2/descancelar/'.$pedido->id) }}">Deshacer cancelación</a>
            @endif
        </div>

    </div>
@endif

</main>



<?php //var_dump($pedido); ?>

@endsection

@push('js')
<!-- <script type="text/javascript" src="{{asset('js/jquery.mobile-1.4.5.js')}}"></script>-->
<script type="text/javascript" src="{{asset('js/jquery.form.js')}}"></script>
<script type="text/javascript" src="{{asset('js/piedramuda.js?x='.rand(0,999))}}"></script>
<script type="text/javascript" src="{{asset('js/attachlist2.js?x='.rand(0,999))}}"></script>
<script>
$(document).ready(function(){
    

    timeoutAccion=null;

    ACCIONHTML="";

    let isMobile = isMobileOrTablet();
if(isMobile){
   // SetEleccionAccionListenerMobile();
}else{
   // SetEleccionAccionListener();
}






$(".Eleccion .Accion.generico").click(function(e){
    e.preventDefault();
    $(".Eleccion .Accion").removeClass("activo");
    $(this).addClass("activo");
    AccionPresionado(this);
});


$(".Eleccion .Accion.enpuerta").click(function(e){
    e.preventDefault();
    $(".Eleccion .Accion").removeClass("activo");
    $(this).addClass("activo");
    AccionPresionadoEnPuerta(this);
});



$(".attachList").each(function(){
    console.log($(this).attr("rel"));
    AttachList($(this).attr("rel"));
});


$("a.cancelar").click(function(e){
    e.preventDefault();

    let tit = $(this).attr("title");
    if(!confirm(tit)){return false;}

    let href=$(this).attr("href");
    window.location.href=href;
});


$("body").on("click", ".modalShow", function(e){
    e.preventDefault();
    let href =$(this).attr("href");
    $.ajax({
        url:href,
        success:function(h){
            MiModal.content(h);
            MiModal.show();
        }
    });
});



$(".NParcial").click(function(e){
e.preventDefault();
let href = $(this).attr("href");
AjaxGet(href,FormaNuevoParcial);
});


$("body").on("click",".editarparcial",function(e){
e.preventDefault();
AjaxGet($(this).attr("href"),FormaEditarParcial);
});

$("body").on("click",".editarsm",function(e){
e.preventDefault();
AjaxGet($(this).attr("href"),FormaEditarSmaterial);
});

$("body").on("click",".editof",function(e){
e.preventDefault();
AjaxGet($(this).attr("href"),FormaEditarOrdenf);
});

$("body").on("click",".editarrequisicion",function(e){
e.preventDefault();
AjaxGet($(this).attr("href"),FormaEditarRequisicion);
});



$("body").on("click",".editapg",function(e){
e.preventDefault();
let spc = $(this).closest(".SubProcesoContainer");
    if(spc.length > 0){
        $(spc).addClass("recargame");
    }
AjaxGet($(this).attr("href"),FormaEditarProcesoGeneral);
});



$(".Candidato").click(function(e){
e.preventDefault();
let href = $(this).attr("href");
let rel = $(this).attr("rel");
    if(rel == "smaterial"){
        AjaxGet(href,FormaNuevoSmaterial);
    }
    else if(rel == "requisicion"){
        AjaxGet(href,FormaNuevoRequisicion);     
    }
    else if(rel == "ordenf"){
        AjaxGet(href,FormaNuevoOrdenf);        
    }
    else if(rel == "devolucion"){
        AjaxGet(href,FormaNuevoDevolucion);        
    }

});





CargarParciales();
CargarSmateriales();
CargarOrdenf();
CargarRequisiciones();
CargarDevoluciones();
//$("#CuerpoActualizar").hide();


});



function FormaNuevoParcial(h){
    MiModal.content(h);
    MiModal.show();

    FormaNuevoParcial2();
}
function FormaNuevoParcial2(){
    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        success:function(h){
            MiModal.content(h);
            MiModal.show();            

            if($("#atlSlot").length>0){
            let uploadto = $("#atlSlot").attr("uploadto");
            let listHref = $("#atlSlot").attr("listHref");
            let val = $("#atlSlot").attr("val");
            AttachListCreate("#atlSlot","nparc",uploadto, listHref,"pictures","partial_id", val, "edit");
            }              

            $("input[name='parcialterminar']").click(function(){
                MiModal.exit();
                CargarParciales();
            });     

        }
    });
}


function FormaEditarParcial(h){
    MiModal.content(h);
   // MiModal.after = FormaEditarParcial2;
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                CargarParciales();
            }else{
                console.log(json);
            }
        }
    });

    let uploadto = $("#atlSlot").attr("uploadto");
    let listHref = $("#atlSlot").attr("listHref");
    let val = $("#atlSlot").attr("val");
    AttachListCreate("#atlSlot","edparcial",uploadto, listHref,"pictures","partial_id", val, "edit"); 
}



function FormaNuevoSmaterial(h){

    MiModal.content(h);
    MiModal.show();

    FormaNuevoSmaterial2();
}
function FormaNuevoSmaterial2(){

    if($("#atlSlot").length>0){
        let uploadto = $("#atlSlot").attr("uploadto");
        let listHref = $("#atlSlot").attr("listHref");
        let val = $("#atlSlot").attr("val");
        let event = $("#atlSlot").attr("event");
        AttachListCreate("#atlSlot","nsmat",uploadto, listHref,"pictures","smaterial_id", val, "edit", event); 
        FormaNuevoSmaterial3();
    }              

    $("input[name='parcialterminar']").click(function(){
        MiModal.exit();
        CargarSmateriales();
    });  

    $("body").on("MiModal-exit",function(){
        CargarSmateriales();
        $("body").off("MiModal-exit");
    });

    $(".AccionForm [name='status_id']").change(function(){
        let val = $(this).val();
        if(val == 5 ){
            $(".AccionForm .hiddenDisclaimer").show();
        }else{
            $(".AccionForm .hiddenDisclaimer").hide();
        }
    });



    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        success:function(h){
            
            MiModal.content(h);
            MiModal.exitButton=false; 
            MiModal.show();     
            FormaNuevoSmaterial2();    
        }
    });

}

function FormaNuevoSmaterial3(){
    $("body").on("activated",".attachList[rel='nsmat']",function(){

        $(".attachList[rel='nsmat']").on("uploaded",function(){
        $("#smTerminar").show();        
        });

    });
}




function FormaEditarSmaterial(h){
    MiModal.exitButton=true;
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                CargarSmateriales();
            }else{
                console.log(json);
            }
        }
    });

    let uploadto = $("#atlSlot").attr("uploadto");
    let listHref = $("#atlSlot").attr("listHref");
    let val = $("#atlSlot").attr("val");

    AttachListCreate("#atlSlot", "edsm_"+val, uploadto, listHref,"pictures","smaterial_id", val, "edit"); 
}




function FormaNuevoOrdenf(h){
    MiModal.content(h);
    MiModal.show();

    FormaNuevoOrdenf2();
}
function FormaNuevoOrdenf2(){
    $("body").bind("MiModal-exit",function(){
        CargarOrdenf();
        $("body").unbind("MiModal-exit");
    });

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
        dataType:"json", 
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                CargarOrdenf();
            }
            else{alert(json.errors);} 
        }
    });

}


function FormaEditarOrdenf(h){
    MiModal.exitButton=true;
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                CargarOrdenf();
            }else{
                console.log(json);
            }
        }
    });

/*
    let uploadto = $("#atlSlot").attr("uploadto");
    let listHref = $("#atlSlot").attr("listHref");
    let val = $("#atlSlot").attr("val");

    AttachListCreate("#atlSlot","edsm_"+val,uploadto, listHref,"pictures","smaterial_id", val, "edit"); 
    */
}





function FormaNuevoRequisicion(h){
    MiModal.content(h);
    MiModal.show();

    FormaNuevoRequisicion2();
}
function FormaNuevoRequisicion2(){
    $("body").bind("MiModal-exit",function(){
        CargarRequisiciones();
        $("body").unbind("MiModal-exit");
    });

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
        dataType:"json", 
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                CargarRequisiciones();
            }
            else{alert(json.errors);} 
        }
    });

}


function FormaEditarRequisicion(h){
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                //CargarOrdenf();
                CargarRequisiciones();
            }else{
                console.log(json);
            }
        }
    });

}


function FormaEditarProcesoGeneral(h){
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                let rme =$(".recargame"); 
                if(rme.length > 0){

                    $(rme).removeClass("recargame");
                    $.get($(rme).attr("href"),function(h){
                        $(rme).html(h);
                        $(rme).find(".attachList").each(function(){
                        AttachList($(this).attr("rel"));
                        });
                    });
                    MiModal.exit();
                }else{
                    window.location.reload();
                }
                
            }else{
                console.log(json);
            }
        }
    });

    $("#dialogo .attachList").each(function(){
        AttachList($(this).attr("rel"));
    });

    $("body").bind("MiModal-exit",function(){
        window.location.reload();
        $("body").unbind("MiModal-exit");
    });

}








function FormaNuevoDevolucion(h){
    MiModal.content(h);
    MiModal.show();

    FormaNuevoDevolucion2();
}
function FormaNuevoDevolucion2(){
    $("body").bind("MiModal-exit",function(){
        CargarRequisiciones();
        $("body").unbind("MiModal-exit");
    });

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
        dataType:"json", 
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                CargarDevoluciones();
            }
            else{alert(json.errors);} 
        }
    });

}


function FormaEditarDevolucion(h){
    MiModal.content(h);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);}, 
        dataType:"json",
        success:function(json){
            if(json.status==1){
                MiModal.exit();
                //CargarOrdenf();
                CargarDevoluciones();
            }else{
                console.log(json);
            }
        }
    });

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
        AccionPresionadoLlena(ob, h);
    }
   });

 }


 function AccionPresionadoLlena(ob,html){
    if(typeof(ob)=="undefined"){ob=null;}
    $(ob).addClass("completo");
    //$("#AccionSection").html(html);

    MiModal.content(html);
    MiModal.show();
    //MiModal(html);
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
                AccionCargarPaso(json.url); 
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



    $("#dialogo .attachList").each(function(){
    AttachList($(this).attr("rel"));
    });

    $("body").on("MiModal-exit",function(){
        window.location.reload();
    });

}

function AccionCargarPaso(href){

$.ajax({
 url:href,
 type:"get",
 error:function(err){alert(err.statusText);},
 success:function(h){
     $("#AccionSection").html(h);

     MiModal.content(h);
     //MiModal.after =FormaAccionSet;
     MiModal.show();

     FormaAccionSet();

     }
});

}



function AccionPresionadoEnPuerta(ob){
   let href = $(ob).attr("href");
   console.log(ob);

   $.ajax({
    url:href,
    type:"get",
    error:function(err){alert(err.statusText);},
    success:function(h){
        AccionEnPuertaForma(h,ob);
    }
   });

 }


 function AccionEnPuertaForma(html,ob){
    if(typeof(ob)=="undefined"){ob=null;}
    else{
        $(ob).addClass("completo");
    }    

    MiModal.content(html);
    MiModal.show();

    $("#FSetAccion").ajaxForm({
        error:function(err){alert(err.statusText);},
        dataType:"json",
        success:function(json){
            if(json.status==1){
                window.location.reload();
            }
            else if(json.status == 2){
                AccionCargarPasoEnPuerta(json.url); 
            }
        }
    });


    $("#FSetAccion .setto").click(function(e){
        e.preventDefault();

        let rel = $(this).attr("rel");
        let val = $(this).attr("val");
        $(this).closest("form").find("[name='"+rel+"']").val(val);
        $(this).closest("form").submit();
    });
}

function AccionCargarPasoEnPuerta(href){

$.ajax({
 url:href,
 type:"get",
 error:function(err){alert(err.statusText);},
 success:function(h){
        
        MiModal.exitButton=false;
        MiModal.content(h);
        MiModal.show();        

        setTimeout(()=>{MiModal.exitButton=true;},100);     

        AttachList("enp");

        $(document).on("click", ".attachList[rel='enp']", function(){
            $("#filaEnpuertaN").show();
            $(document).off("click", ".attachList[rel='enp']");
        });


        $("body").on("MiModal-exit",function(){
        window.location.reload();
        });


     }
});

}












 function MostrarActualizar(){
    $("#CuerpoActualizar").slideDown();
 }


function CargarParciales(){
    let href = $("#ParcialesDiv").attr("href");

    $.ajax({
        url:href,
        error:function(err){alert(err.statusText);},
        success:function(h){
            $("#ParcialesDiv").html(h);
            $("#ParcialesDiv .attachList").each(function(){
                AttachList($(this).attr("rel"));
            });
        }
    });
    
}

function CargarSmateriales(){
    let href = $("#SmaterialDiv").attr("href");

    $.ajax({
        url:href,
        error:function(err){alert(err.statusText);},
        success:function(h){
            $("#SmaterialDiv").html(h);
            $("#SmaterialDiv .attachList").each(function(){
                AttachList($(this).attr("rel"));
            });
        }
    });
    
}

function CargarOrdenf(){
    let href = $("#OrdenfDiv").attr("href");

    $.ajax({
        url:href,
        error:function(err){alert(err.statusText);},
        success:function(h){
            $("#OrdenfDiv").html(h);
            /*
            $("#OrdenfDiv .attachList").each(function(){
                AttachList($(this).attr("rel"));
            });
            */
        }
    });
    
}


function CargarRequisiciones(){
    let href = $("#RequisicionDiv").attr("href");

    $.ajax({
        url:href,
        error:function(err){alert(err.statusText);},
        success:function(h){
            $("#RequisicionDiv").html(h);
            /*
            $("#OrdenfDiv .attachList").each(function(){
                AttachList($(this).attr("rel"));
            });
            */
        }
    });
    
}

function CargarDevoluciones(){
    let href = $("#DevolucionDiv").attr("href");

    $.ajax({
        url:href,
        error:function(err){alert(err.statusText);},
        success:function(h){
            $("#DevolucionDiv").html(h);
            
            $("#DevolucionDiv .attachList").each(function(){
                AttachList($(this).attr("rel"));
            });
            
        }
    });
    
}




</script>    
@endpush
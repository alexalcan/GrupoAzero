<?php
use App\Libraries\Paginacion;


?>
@foreach ($lista as $item)

{{ view("pedidos2.pedido_item",compact("item","estatuses")) }}

@endforeach


@if (count($lista)==0) 
    <p>No se encontraron resultados con esos filtros.</p>
@endif

<?php

Paginacion::total($total);
Paginacion::actual($pag);
Paginacion::rpp($rpp);
Paginacion::items("resultados");
Paginacion::calc();
echo Paginacion::render_largo(url("pedidos2/lista"));
?>


<aside class="AccionForm">

<div class="Fila">
    @if ($order->origin == "F")
    Es indispensable que exista la factura, folio y documento, para poder crear una requisición.
    @elseif ($order->origin == "C")
    Es indispensable que exista la cotizacion, folio y documento, para poder crear una requisición.
    @else

    @endif
</div>

</aside>
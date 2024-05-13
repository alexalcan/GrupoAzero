<?php
use App\Libraries\Tools;
?>
<b>Historial de pedido {{ isset($order->invoice_number) ? $order->invoice_number : $order->invoice }}</b>
<div class="ScrollHistorial">
    <table class="TablaHistorial" cellspacing='0' cellpadding='4' border='1'>
    @foreach ($lista as $li)
        <tr>

            <td>
                <div><b>{{ $li->status }}</b></div>
                <div>{{ $li->action }}</div>
            </td>
            <td>{{ Tools::fechaMedioLargo($li->created_at)  }}</td>
        </tr>
    @endforeach    
    </table>

</div>
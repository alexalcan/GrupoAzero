
<?php
use App\Libraries\Tools;

?>
<div class="InfoBox">
<aside class="InfoTR">
        <div class="th">Status</div>
        <div class="th">Fecha</div>
        <div class="th">Usuario</div>
        <div class="th">Detalle</div>
</aside>
    @foreach ($logs as $log) 

    <aside class="InfoTR">
        <div class="td"><span>Status</span> {{$log->status}}</div>
        <div class="td"><span>Fecha</span> {{ Tools::fechaLatin($log->created_at) }}</div>
        <div class="td"><span>Usuario</span> {{ $log->user }}</div>
        <div class="td"><span>Detalle</span> {{$log->action}}</div>
    </aside>

    @endforeach
    
</div>
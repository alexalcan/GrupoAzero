<?php
use App\Libraries\Tools;
//var_dump($user->department);
?>
@if ( in_array($user->department->id, [2,4]))
@foreach ($shipments as $ship)
    <section class='Proceso'>
        <h3>Evidencia paso por puerta {{ '# ' . $ship->id. " " . Tools::fechaLatin($ship->created_at) }}</h3>

        <section class='attachList' rel='ship{{ $ship->id }}' 
        uploadto="{{ url('pedidos2/attachpost?catalog=pictures&shipment_id='.$ship->id) }}" 
        href="{{ url('pedidos2/attachlist?rel=ship'. $ship->id .'&catalog=pictures&shipment_id='.$ship->id) }}"></section> 
 

    </section>
@endforeach

@endif



@if(in_array($user->department->id,[1,2,3,5,7]))

@foreach ($shipments as $ship)
    <section class='Proceso'>
        <h3>Evidencia paso por puerta  #{{ $ship->id. " " . Tools::fechaLatin($ship->created_at) }}</h3>
       
        @foreach ($pictures as $pic)
   
            @if ($pic->shipment_id == $ship->id)
            <a  href="{{ asset('storage/'.$pic->picture) }}" target='_blank'><img src="{{ asset('storage/'.$pic->picture) }}" height="80" /></a>
            @endif
        @endforeach

    </section>
@endforeach


@endif
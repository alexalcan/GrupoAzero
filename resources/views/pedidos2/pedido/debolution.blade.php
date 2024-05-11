@if (!empty($debolutions))
<section class="Proceso">
<h3>Devolución</h3>

    @foreach ($debolutions as $debolution)

        <p>{{ $debolution->reason }}</p>

        @foreach ($evidences as $ev)
        @if (!empty($ev->file) && $ev->debolution_id == $debolution->id )
        <div><img src="{{ asset( 'storage/'.$ev->file) }}" /></div>
        @endif
        @endforeach

    @endforeach
    
    </section>
@endif
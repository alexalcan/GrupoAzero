<aside class="Notes">
<div><b>Notas</b></div>
@foreach ($list as $note)
<div class="Note">
        <p>{{$note->note}}</p>
        <div><small>{{ $note->getUserOf($note->id)->name }} {{\App\Libraries\Tools::fechaMedioLargo($note->created_at) }}</small></div>
</div>
@endforeach

</aside>
@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')
    <div class="content">
        <div class="container-fluid">

            @if ( auth()->user()->role->name == "Empleado" )
                <div class="row">
                    {{-- <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">group</i>
                            </div>
                            <p class="card-category">Usuarios</p>
                            <h3 class="card-title">{{ $users }}
                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            <a href="#pablo">Usuarios activos en plataforma</a>
                            </div>
                        </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">receipt</i>
                            </div>
                            <p class="card-category">Pedidos por departamento</p>
                            <h3 class="card-title">{{ $orders->count() }}</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            {{-- <i class="material-icons">date_range</i>  --}}
                            Todos los pedidos del departamento de {{ auth()->user()->department->name }}
                            </div>
                        </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-danger card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">info_outline</i>
                            </div>
                            <p class="card-category">Fixed Issues</p>
                            <h3 class="card-title">75</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            <i class="material-icons">local_offer</i> Tracked from Github
                            </div>
                        </div>
                        </div>
                    </div> --}}
                    {{-- <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-info card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">manage_search</i>
                            </div>
                            <p class="card-category">Transacciones</p>
                            <h3 class="card-title">{{ $logs->count() }}</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            Bitácora de transaciones
                            </div>
                        </div>
                        </div>
                    </div> --}}
                </div>
            @elseif( auth()->user()->role->name == "Administrador" )
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">group</i>
                            </div>
                            <p class="card-category">Grupo</p>
                            <h3 class="card-title">{{ $users }}
                            {{-- <small>GB</small> --}}
                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            {{-- <i class="material-icons text-danger">warning</i> --}}
                            <a href="#pablo">Usuarios activos en plataforma</a>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">receipt</i>
                            </div>
                            <p class="card-category">Pedidos</p>
                            <h3 class="card-title">{{ $orders }}</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            {{-- <i class="material-icons">date_range</i>  --}}
                            Todos los pedidos
                            </div>
                        </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-danger card-header-icon">
                            <div class="card-icon">
                            <i class="material-icons">info_outline</i>
                            </div>
                            <p class="card-category">Fixed Issues</p>
                            <h3 class="card-title">75</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            <i class="material-icons">local_offer</i> Tracked from Github
                            </div>
                        </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="card card-stats">
                        <div class="card-header card-header-info card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">manage_search</i>
                            </div>
                            <p class="card-category">Transacciones</p>
                            <h3 class="card-title">{{ $logs->count() }}</h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            {{-- <i class="material-icons">update</i>  --}}
                            Bitácora de transaciones
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

            @endif

            <div class="row">
                @if ( auth()->user()->role->name != "Cliente" )
                <div class="col-md-6">
                @else
                <div class="col-md-12">
                @endif
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <div class="row">
                                <div class="col-12 text-left">
                                    {{-- <h4 class="card-title ">Buscar Factura</h4> --}}
                                    {{-- <p class="card-category"> Todos los movimientosa</p> --}}
                                    <form class="navbar-form" method="POST" action="{{ route('search') }}">
                                        @csrf
                                        @method('get')
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group no-border">
                                                    <input type="text" name="invoice" value="" class="form-control" placeholder="Orden..." style="color: white">
                                                    @if ( auth()->user()->role->name != "Cliente" )
                                                        <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                                            <i class="material-icons">search</i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if ( auth()->user()->role->name == "Cliente" )
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="input-group no-border">
                                                        <input type="hidden" name="user" value="client" style="color: white">
                                                        <input type="text" name="client" value="" class="form-control" placeholder="Identificador del cliente..." style="color: white">
                                                        <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                                        <i class="material-icons">search</i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                                {{-- <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                                        <span class="material-icons">
                                            person_add
                                        </span>
                                        Nuevo usuario
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive ">
                                {{-- <form class="navbar-form" method="POST" action="{{ route('search') }}">
                                    @csrf
                                    @method('get')
                                    <span class="bmd-form-group">
                                    <div class="input-group no-border">
                                        <input type="text" name="invoice" value="" class="form-control" placeholder="Buscar...">
                                        <button type="submit" class="btn btn-white btn-round btn-just-icon">
                                        <i class="material-icons">search</i>
                                        <div class="ripple-container"></div>
                                        </button>
                                    </div></span>
                                </form> --}}
                                @if ( !(isset($order)) )
                                    <p>De momento no hay nada por aquí, asegurate introducir un número de factura válido</p>
                                @else
                                    <table class="table">
                                        <thead>
                                            <th>Factura</th>
                                            <th>Estatus actual</th>
                                            <th>&nbsp;</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <a href="{{ route('orders.show', $order->id) }}">{{ $order->invoice }}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('orders.show', $order->id) }}">{{ $order->status->name }}</a>
                                                </td>
                                                <td>
                                                    @if ( isset($follow) )
                                                        <a href="{{ route('orders.show', $order->id) }}">
                                                            <input type="hidden" name="action" value="unfollow">
                                                            <button type="submit" class="btn btn-primary btn-link btn-fab btn-fab-mini btn-round">
                                                                <i class="material-icons">favorite</i>
                                                            </button>
                                                        </a>
                                                    @else
                                                        <form method="post" action="{{ route('follows.update', $order->id) }}">
                                                            @csrf
                                                            @method('put')
                                                            <input type="hidden" name="action" value="follow">
                                                            <button type="submit" class="btn btn-primary btn-link btn-fab btn-fab-mini btn-round">
                                                                <i class="material-icons">favorite_border</i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                                @if ( $plural == 1 )
                                    <table class="table">
                                        <thead>
                                            <th>Factura</th>
                                            <th>Estatus actual</th>
                                            {{-- <th>&nbsp;</th> --}}
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('orders.show', $order->id) }}">{{ $order->invoice }}</a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('orders.show', $order->id) }}">{{ $order->status->name }}</a>
                                                    </td>
                                                    {{-- <td>
                                                        @if ( isset($follow) )
                                                            <a href="{{ route('orders.show', $order->id) }}">
                                                                <input type="hidden" name="action" value="unfollow">
                                                                <button type="submit" class="btn btn-primary btn-link btn-fab btn-fab-mini btn-round">
                                                                    <i class="material-icons">favorite</i>
                                                                </button>
                                                            </a>
                                                        @else
                                                            <form method="post" action="{{ route('follows.update', $order->id) }}">
                                                                @csrf
                                                                @method('put')
                                                                <input type="hidden" name="action" value="follow">
                                                                <button type="submit" class="btn btn-primary btn-link btn-fab btn-fab-mini btn-round">
                                                                    <i class="material-icons">favorite_border</i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if ( auth()->user()->role->name != "Cliente" )
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title ">Notas</h4>
                                        <p class="card-category"> Aquí se despliegan sus notas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    @if ( !(isset($order)) )
                                        <p>De momento no hay nada por aquí, asegurate introducir un número de factura válido</p>
                                    @else
                                        <table class="table">
                                            <thead>
                                                <th>Usuario</th>
                                                <th>Nota</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->notes as $note)
                                                    <tr>
                                                        <td>
                                                            {{ $note->user->name }}
                                                        </td>
                                                        <td>
                                                            {{ $note->note }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if ( auth()->user()->role->name == "Administrador" )
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title ">Bitácora</h4>
                                        <p class="card-category"> Todos los movimientosa</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table class="table" id="bitacora">
                                        <thead>
                                            <tr>
                                                <th width="150px">Fecha</th>
                                                <th width="150px">Usuario</th>
                                                <th width="120px">Pedido</th>
                                                <th width="150px">Departamento</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($logs as $log)
                                                <tr>
                                                    <td>{{ $log->created_at->calendar() }}</td>
                                                    <td>{{ $log->user->name }}</td>
                                                    <td>
                                                        <a href="{{ route('orders.show', $log->order->id) }}">{{ $log->order->invoice }}</a>
                                                        {{-- {{ $log->order->invoice }} --}}
                                                    </td>
                                                    <td>{{ $log->department->name }}</td>
                                                    <td>{{ $log->action }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
  </script>

<script>
    $(document).ready(function() {
        $('#bitacora').DataTable({
            // "serverSide": true,
            // "ajax": "{{ url('api/users') }}",
            // "columns": [
            //     {data: 'id'},
            //     {data: 'name'},
            //     {data: 'email'},
            //     {data: 'btn'},
            // ],
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });
    });
</script>
@endpush

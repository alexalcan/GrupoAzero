@extends('layouts.app', ['activePage' => 'roles', 'titlePage' => __('Roles')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('roles.update', $role->id) }}">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title">Editar rol</h4>
                                        <p class="card-category">Información básica del rol</p>
                                    </div>
                                    <div class="col-md-8 col-sm-12 col-xs-12 text-right">
                                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary">
                                            <span class="material-icons">
                                                arrow_back
                                            </span>

                                            Regresar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Nombre</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <input class="form-control" name="name" id="input-name" type="text" placeholder="Name" value="{{ $role->name }}" required="true" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('roles.destroy', $role->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Zona peligrosa</h4>
                            <p class="card-category">Borrar rol</p>
                        </div>
                        <div class="card-body text-center">
                            <h6>Al eliminar el rol, no podrá ser recuperado</h6>
                            <p>Actualmente hay {{ $users->count() }} con el rol de {{ $role->name }}. Asegurate de que ningún usuario tiene el rol para poder proceder a su eliminación</p>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            @if ( $users->count() > 0 )
                                <button type="submit" class="btn btn-danger" disabled>Entiendo, borrar el rol</button>
                            @else
                                <button type="submit" class="btn btn-danger">Entiendo, borrar rol</button>
                            @endif

                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

@endpush


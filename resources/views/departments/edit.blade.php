@extends('layouts.app', ['activePage' => 'departments', 'titlePage' => __('Departamentos')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('departments.update', $department->id) }}">
                        @csrf
                        @method('put')
                        <div class="card">
                            <div class="card-header card-header-primary">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12 text-left">
                                        <h4 class="card-title">Editar departamento</h4>
                                        <p class="card-category">Información básica del departamento</p>
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
                                            <input class="form-control" name="name" id="input-name" type="text" placeholder="Name" value="{{ $department->name }}" required="true" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-2 col-form-label">Descripción</label>
                                    <div class="col-sm-7">
                                        <div class="form-group bmd-form-group is-filled">
                                            <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3">{{ $department->description }}</textarea>
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
                    <form method="post" action="{{ route('departments.destroy', $department->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Zona peligrosa</h4>
                            <p class="card-category">Borrar departamento</p>
                        </div>
                        <div class="card-body text-center">
                            <h6>Al eliminar el departamento, no podrá ser recuperado</h6>
                            <p>Actualmente hay {{ $users->count() }} que pertenecen al departamento de {{ $department->name }}. Asegurate de que ningún usuario esté en ese departamento para poder proceder a su eliminación</p>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            @if ( $users->count() > 0 )
                                <button type="submit" class="btn btn-danger" disabled>Entiendo, borrar departamento</button>
                            @else
                                <button type="submit" class="btn btn-danger">Entiendo, borrar departamento</button>
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


@extends('layout')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-8">

            <a href="{{ BASE_URL }}/permissions/create" class="btn btn-primary mt-3 mb-3"><i class="bi bi-person-fill-gear"></i> Crear Nuevo Permiso</a>

            <hr>

            <h1>Lista de Permisos</h1>

            @if(isset($permissions) && count($permissions) > 0)
            <div class="table-responsive-sm">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Permission name</th>
                            <th colspan="2" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value['clave'] }}</td>
                                <td class="text-center">
                                    <a href="{{ BASE_URL }}/permissions/{{ $value['id'] }}/edit" class="btn btn-sm btn-success" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ BASE_URL }}/admin/permissions/permissionForm.php?delete_permission={{ $value['id'] }}" class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center">
                Aún no se han registrado Permisos.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@extends('layout')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-8">

            <a href="{{ BASE_URL }}/roles/create" class="btn btn-primary mt-3 mb-3"><i class="bi bi-person-fill-gear"></i> Crear Nuevo Rol</a>

            <hr>

            <h1>Lista de Roles</h1>

            @if(isset($roles) && count($roles) > 0)
            <div class="table-responsive-sm">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>N</th>
                            <th>Role name</th>
                            <th colspan="3" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value['name'] }}</td>
                                <td class="text-center">
                                    <a href="{{ BASE_URL }}/admin/roles/assignPermissions.php?assign_permissions={{ $value['id'] }}" class="btn btn-sm btn-info" title="Permisos">
                                        <!-- <i class="bi bi-person-fill-lock"></i> -->
                                        <i class="bi bi-shield-lock-fill"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ BASE_URL }}/admin/roles/roleForm.php?edit_role={{ $value['id'] }}" class="btn btn-sm btn-success" title="Editar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ BASE_URL }}/admin/roles/roleForm.php?delete_role={{ $value['id'] }}" class="btn btn-sm btn-danger" title="Eliminar">
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
                Aún no se han registrado Roles.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
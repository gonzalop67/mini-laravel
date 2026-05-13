@extends('layout')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-8">

            <a href="{{ BASE_URL }}/roles/create" class="btn btn-primary mt-3 mb-3"><i class="bi bi-person-fill-gear"></i> Crear Nuevo Rol</a>

            <hr>

            <h1>Lista de Roles</h1>

            <?php if (isset($_SESSION['mensaje'])) { ?>
                <div class="alert alert-<?= isset($_SESSION['tipo']) ? $_SESSION['tipo'] : 'danger' ?> d-flex align-items-center" role="alert">
                    <i class="bi bi-<?= isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'success' ? 'check-circle-fill' : 'x-circle-fill' ?> me-2 fs-4"></i>
                    <div>
                        <?php echo isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '' ?>
                    </div>
                </div>
            <?php } ?>

            <?php if (isset($_SESSION['mensaje'])) unset($_SESSION['mensaje']) ?>
            <?php if (isset($_SESSION['tipo'])) unset($_SESSION['tipo']) ?>
            <?php if (isset($_SESSION['icono'])) unset($_SESSION['icono']) ?>

            @if(isset($roles) && count($roles) > 0)
            <div class="table-responsive-sm">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
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
                                <a href="{{ BASE_URL }}/roles/{{ $value['id'] }}/permissions" class="btn btn-sm btn-info" title="Permisos">
                                    <i class="bi bi-person-fill-lock"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ BASE_URL }}/roles/{{ $value['id'] }}/edit" class="btn btn-sm btn-success" title="Editar">
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
@extends('layout')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Lista de Usuarios</h1>

            <?php
            $search = isset($_GET['search']) ? $_GET['search'] : "";
            ?>

            <nav class="navbar bg-light">
                <div class="container-fluid">
                    <?php if (tiene_permiso('crear-usuario')): ?>
                        <a href="<?= BASE_URL ?>/users/create" class="navbar-brand btn btn-primary btn-sm mb-3"><i class="bi bi-person-fill-add"></i> Nuevo Usuario</a>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/users" class="d-flex" role="search">
                        <input class="form-control me-2" type="search" name="search" value="{{ $search }}" placeholder="Usuario a buscar..." aria-label="Search">
                        <button class="btn btn-outline-primary" type="submit">Buscar</button>
                    </form>
                </div>
            </nav>

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

            @if(count($users) > 0)
            <div class="table-responsive-sm">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th class="text-center">Roles</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $contador = 0;
                        ?>
                        @foreach($users['data'] as $user)
                        <?php
                        $contador++;
                        ?>
                        <tr>
                            <td>{{ $contador }}</td>
                            <td>{{ $user['username'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td class="text-center">
                                <a href="{{ BASE_URL }}/users/{{ $user['id'] }}/roles" class="btn btn-sm btn-primary" title="Roles">
                                    <i class="bi bi-person-fill-gear"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="#" type="button" class="btn btn-info btn-sm" title="Mostrar Usuario"><i class="bi bi-eye"></i></a>
                                    <?php if (tiene_permiso('actualizar-usuario')): ?>
                                        <a href="#" type="button" class="btn btn-success btn-sm" title="Editar Usuario"><i class="bi bi-pencil"></i></a>
                                    <?php endif; ?>

                                    <?php if (tiene_permiso('eliminar-usuario')): ?>
                                        <button type="submit" class="btn btn-danger btn-sm item-delete" data-id="{{ $user['id'] }}" title="Eliminar Usuario"><i class="bi bi-trash"></i></button>
                                    <?php endif; ?>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <?php
            $paginate = 'users';
            ?>
            @include('assets.pagination')
            @else
            <div class="text-center">
                Aún no se han registrado Usuarios.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
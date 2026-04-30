@extends('layout')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Lista de Usuarios</h1>
            <a href="<?= BASE_URL ?>/users/create">Nuevo Usuario</a>
            <?php if (count($users) > 0) { ?>
                <div class="table-responsive-sm">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            foreach ($users as $user) {
                                $contador++; ?>
                                <tr>
                                    <td><?= $contador ?></td>
                                    <td><?= $user['username'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="text-center">
                    Aún no se han registrado Usuarios.
                </div>
            <?php } ?>
        </div>
    </div>
</div>
@endsection
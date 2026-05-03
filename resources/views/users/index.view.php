@extends('layout')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Lista de Usuarios</h1>
            <a href="{{ BASE_URL }}/users/create" class="btn btn-success mb-3"><i class="bi bi-person-fill-add"></i> Nuevo Usuario</a>
            @if(count($users) > 0)
            <div class="table-responsive-sm">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $contador = 0;
                        ?>
                        @foreach($users as $user)
                        <?php
                        $contador++;
                        ?>
                        <tr>
                            <td>{{ $contador }}</td>
                            <td>{{ $user['username'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td></td>
                            <td style="text-align: center">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="#" type="button" class="btn btn-info btn-sm" title="Mostrar Usuario"><i class="bi bi-eye"></i></a>
                                    <a href="#" type="button" class="btn btn-success btn-sm" title="Editar Usuario"><i class="bi bi-pencil"></i></a>

                                    <button type="submit" class="btn btn-danger btn-sm item-delete" data-id="{{ $user['id'] }}" title="Eliminar Usuario"><i class="bi bi-trash"></i></button>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center">
                Aún no se han registrado Usuarios.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@extends('layout')

@section('contenido')
<div class="container">
    <h1>Lista de Contactos</h1>

    <a href="{{ BASE_URL }}/contacts/create" class="btn btn-primary mb-3"><i class="bi bi-person-lines-fill"></i> Crear contacto</a>

    <ul>
        @foreach($contacts as $contact)
            <li>
                <a href="{{ BASE_URL }}/contacts/{{ $contact['id'] }}">
                    {{ $contact['name'] }} - {{ $contact['email'] }}
                </a>
            </li>
        @endforeach
    </ul>
    <!-- <h2>Agregar Contacto</h2>
            <form action="<?= BASE_URL ?>/contacts" method="post">
                <input type="text" name="name" placeholder="Nombre" required>
                <input type="email" name="email" placeholder="Email" required>
                <button type="submit">Agregar</button>
            </form> -->
    <a href="<?= BASE_URL ?>">Inicio</a>
</div>
@endsection
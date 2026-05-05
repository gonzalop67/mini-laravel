@extends('layout')

@section('contenido')
<div class="container">
    <h1>Lista de Contactos</h1>

    <nav class="navbar bg-light">
        <div class="container-fluid">

            <a href="{{ BASE_URL }}/contacts/create" class="navbar-brand btn btn-primary mb-3"><i class="bi bi-person-lines-fill"></i> Crear contacto</a>

            <form action="{{ BASE_URL }}/contacts" class="d-flex" role="search">
                <input class="form-control me-2" type="search" name="search" placeholder="Contacto a buscar..." aria-label="Search">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
            </form>
        </div>
    </nav>

    <ul>
        @foreach($contacts['data'] as $contact)
        <li>
            <a href="{{ BASE_URL }}/contacts/{{ $contact['id'] }}">
                {{ $contact['name'] }} - {{ $contact['email'] }}
            </a>
        </li>
        @endforeach
    </ul>

    <?php
    $paginate = 'contacts';
    ?>

    @include('assets.pagination')

</div>
@endsection
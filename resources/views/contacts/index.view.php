@extends('layout')

@section('contenido')
<div class="container">
    <h1>Lista de Contactos</h1>

    <a href="{{ BASE_URL }}/contacts/create" class="btn btn-primary mb-3"><i class="bi bi-person-lines-fill"></i> Crear contacto</a>

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
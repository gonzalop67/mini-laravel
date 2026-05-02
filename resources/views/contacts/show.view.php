@extends('layout')

@section('contenido')
<div class="container">
    <h1>Detalle del Contacto</h1>
    <a href="{{ BASE_URL }}/contacts" class="btn btn-success mb-3"><i class="bi bi-skip-backward-btn-fill"></i> Volver</a>
    <a href="{{ BASE_URL }}/contacts/{{ $contact['id'] }}/edit" class="btn btn-primary mb-3"><i class="bi bi-pencil-square"></i> Editar</a>
    <p>Nombre: {{ $contact['name'] }}</p>
    <p>Email: {{ $contact['email'] }}</p>
    <p>Phone: {{ $contact['phone'] }}</p>
    <form action="{{ BASE_URL }}/contacts/{{ $contact['id'] }}/delete" method="post">
        <button type="submit" class="btn btn-danger">
            <i class="bi bi-person-x-fill"></i> Eliminar
        </button>
    </form>
</div>
@endsection
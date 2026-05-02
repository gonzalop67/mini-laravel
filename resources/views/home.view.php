@extends('layout')

@section('contenido')
<div class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Bienvenido a Mini Laravel</h1>
                <p>Esta es una mini versión de Laravel.</p>
                @if(isset($_SESSION['authenticated']))
                    <p>Bienvenido: {{ $_SESSION['username'] }}</p>
                    <!-- <a href="<?= BASE_URL ?>/users">Ver Usuarios</a> -->
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
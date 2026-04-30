<?php include './../resources/views/includes/header.view.php' ?>
<?php include './../resources/views/includes/navbar.view.php' ?>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Nuevo Usuario</h1>
            <a href="<?= BASE_URL ?>/users">Lista de Usuarios</a>
            <h2>Ingresar nuevo Usuario</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
            <form action="<?= BASE_URL ?>/users" method="POST">
                <div class="form-group mb-3">
                    <label for="username">Nombre de Usuario</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Ingrese nuevo nombre de usuario" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Correo electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Ingrese correo electrónico" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese su nuevo Password">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<?php include './../resources/views/includes/footer.view.php' ?>
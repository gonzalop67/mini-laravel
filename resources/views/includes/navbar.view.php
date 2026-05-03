<div class="bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-lg navbar-dark">
                    <a class="navbar-brand" href="#">Mini Laravel</a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <ul class="navbar-nav ms-auto">
                            
                            <?php if (!isset($_SESSION['authenticated'])): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>/showLoginForm">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>/showregisterForm">Registrarse</a>
                                </li>
                            <?php endif ?>

                            <?php if (isset($_SESSION['authenticated'])): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>/users">Usuarios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>/roles">Roles</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>/contacts">Contactos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= BASE_URL ?>/auth/logout">Salir</a>
                                </li>
                            <?php endif ?>
                        </ul>

                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
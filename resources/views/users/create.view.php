@extends('layout')

@section('contenido')
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
            <form id="frmInsert" action="" method="POST">
                <div class="form-group mb-3">
                    <label for="username">Nombre de Usuario</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Ingrese nuevo nombre de usuario" required>
                    <p id="error-username" class="invalid-feedback"></p>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Correo electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Ingrese correo electrónico" autocomplete="username" required>
                    <p id="error-email" class="invalid-feedback"></p>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese su nuevo Password" autocomplete="current-password" required>
                    <p id="error-password" class="invalid-feedback"></p>
                </div>

                <div id="img_loader" class="mb-3" style="display:none;text-align:center;">
                    <img src="<?= BASE_URL ?>/public/assets/images/ajax-loader-blue.GIF" alt="Procesando...">
                </div>

                <div id="mensaje">
                    <!-- Aqui van los mensajes de error -->
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<script>
    const base_url = "<?php echo BASE_URL; ?>";

    const form = document.getElementById("frmInsert");

    const username = document.getElementById("username");
    const email = document.getElementById("email");
    const password = document.getElementById("password");

    const mensaje = document.getElementById("mensaje");
    const img_loader = document.getElementById("img_loader");

    // Escuchar el evento submit
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío automático

        // Elimino algún mensaje de error previo
        document.querySelector("#mensaje").innerHTML = "";

        const username_value = username.value.trim();
        const email_value = email.value.trim();
        const password_value = password.value.trim();

        if (username_value == "" || email_value == "" || password_value == "") {
            if (username_value == "") {
                username.classList.add("is-invalid");
                document.getElementById("error-username").innerHTML = "El campo Nombre de Usuario es obligatorio.";
            } else {
                username.classList.remove("is-invalid");
                document.getElementById("error-username").innerHTML = "";
            }

            if (email_value == "") {
                email.classList.add("is-invalid");
                document.getElementById("error-email").innerHTML = "El campo Correo electrónico es obligatorio.";
            } else {
                email.classList.remove("is-invalid");
                document.getElementById("error-email").innerHTML = "";
            }

            if (password_value == "") {
                password.classList.add("is-invalid");
                document.getElementById("error-password").innerHTML = "El campo Contraseña es obligatorio.";
            } else {
                password.classList.remove("is-invalid");
                document.getElementById("error-password").innerHTML = "";
            }
        } else if (!validarEmail(email_value)) {
            email.classList.add("is-invalid");
            document.getElementById("error-email").innerHTML = "Por favor ingrese un correo electrónico válido.";
        } else if (username_value.length < 5) {
            username.classList.add("is-invalid");
            document.getElementById("error-username").innerHTML = "El campo Nombre de Usuario debe tener al menos 5 caracteres.";
        } else if (password_value.length < 5) {
            password.classList.add("is-invalid");
            document.getElementById("error-password").innerHTML = "El campo Contraseña debe tener al menos 5 caracteres.";
        } else if (!validarUsername(username_value)) {
            username.classList.add("is-invalid");
            document.getElementById("error-username").innerHTML = "Por favor ingrese solo caracteres alfanuméricos entre 5 y 64 caracteres.";
        } else {
            registrar_usuario();
        }
    });

    function validarUsername(username) {
        const reg_nombres = /^([a-zA-Z0-9 ñáéíóúÑÁÉÍÓÚ]{5,64})$/i;
        return reg_nombres.test(username);
    }

    function validarEmail(email) {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return regex.test(email);
    }

    async function registrar_usuario() {
        // Eliminar todos los mensajes de error
        username.classList.remove("is-invalid");
        document.getElementById("error-username").innerHTML = "";
        email.classList.remove("is-invalid");
        document.getElementById("error-email").innerHTML = "";
        password.classList.remove("is-invalid");
        document.getElementById("error-password").innerHTML = "";
        // Desplegar el loader image
        document.querySelector("#img_loader").style.display = "block";
        // Obtener todos los campos a enviar mediante FormData
        const data = new FormData(form);
        // Llamar al método store del controlador UserController que inserta el nuevo usuario en la BD
        try {
            let resp = await fetch(base_url + "/users", {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                body: data,
            });
            json = await resp.json();
            if (!json.error) {
                window.location = base_url + "/users";
            } else {
                //Existen errores de validación

                let errors = "";

                Object.entries(json.errors).forEach(([clave, valor]) => {
                    console.log(`${clave}: ${valor}`);
                    errors = errors + `<li>${valor}</li>`
                });

                var error = '<div class="alert alert-danger" role="alert">' +
                    '<p><i class="bi bi-ban"></i> Existen errores:</p>' +
                    '<ul>' +
                    errors +
                    '</ul>' +
                    '</div>';
                img_loader.style.display = "none";
                mensaje.innerHTML = error;
            }
        } catch (error) {
            console.log("Ocurrió un error: " + error)
        }
    }
</script>
@endsection

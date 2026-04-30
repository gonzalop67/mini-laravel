@extends('layout')

@section('contenido')
<div class="py-5">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
            <h4 class="text-center mb-4">Iniciar Sesión</h4>

            <form id="frmLogin" action="" method="POST" autocomplete="off">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="name@example.com" autocomplete="username" required>
                    <label for="email"><i class="bi bi-envelope-fill"></i> Correo electrónico</label>
                    <p id="error-email" class="invalid-feedback"></p>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" placeholder="Contraseña" autocomplete="current-password" required>
                    <label for="password"><i class="bi bi-person-fill-lock"></i> Contraseña</label>
                    <p id="error-password" class="invalid-feedback"></p>
                </div>

                <div id="img_loader" class="mb-3" style="display:none;text-align:center;">
                    <img src="<?= BASE_URL ?>/public/assets/images/ajax-loader-blue.GIF" alt="Procesando...">
                </div>

                <div id="mensaje">
                    <!-- Aqui van los mensajes de error -->
                </div>

                <button class="btn btn-primary w-100" type="submit">Entrar</button>
            </form>
        </div>
    </div>
</div>
<script>
    const base_url = "<?php echo BASE_URL; ?>";

    const form = document.getElementById("frmLogin");

    const email = document.getElementById("email");
    const password = document.getElementById("password");

    const mensaje = document.getElementById("mensaje");
    const img_loader = document.getElementById("img_loader");

    // Escuchar el evento submit
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío automático

        // Elimino algún mensaje de error previo
        document.querySelector("#mensaje").innerHTML = "";

        if (email.value == "" || password.value == "") {
            if (email.value == "") {
                email.classList.add("is-invalid");
                document.getElementById("error-email").innerHTML = "El campo Correo electrónico es obligatorio.";
            } else {
                email.classList.remove("is-invalid");
                document.getElementById("error-email").innerHTML = "";
            }

            if (password.value == "") {
                password.classList.add("is-invalid");
                document.getElementById("error-password").innerHTML = "El campo Contraseña es obligatorio.";
            } else {
                password.classList.remove("is-invalid");
                document.getElementById("error-password").innerHTML = "";
            }
        } else if (!validarEmail(email.value)) {
            email.classList.add("is-invalid");
            document.getElementById("error-email").innerHTML = "Por favor ingrese un correo electrónico válido.";
        } else {
            verificar_login();
        }
    });

    function validarEmail(email) {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return regex.test(email);
    }

    async function verificar_login() {
        // Eliminar todos los mensajes de error
        email.classList.remove("is-invalid");
        document.getElementById("error-email").innerHTML = "";
        password.classList.remove("is-invalid");
        document.getElementById("error-password").innerHTML = "";
        // Desplegar el loader image
        document.querySelector("#img_loader").style.display = "block";
        // Obtener todos los campos a enviar mediante FormData
        const data = new FormData();
        data.append("email", email.value);
        data.append("password", password.value);
        // Llamar al método auth/login que verifica si existe el email y clave
        try {
            let resp = await fetch("<?php echo BASE_URL ?>/auth/login", {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                body: data,
            });
            json = await resp.json();
            if (!json.error) {
                window.location = "<?php echo BASE_URL ?>/auth/dashboard";
            } else {
                //No existe el usuario
                var error = '<div class="alert alert-danger" role="alert">' +
                    '<p><i class="bi bi-ban"></i> ' + json.mensaje + '</p>' +
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
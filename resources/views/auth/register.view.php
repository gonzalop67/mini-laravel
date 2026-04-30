<?php include './../resources/views/includes/header.view.php' ?>
<?php include './../resources/views/includes/navbar.view.php' ?>
<div class="py-5">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card shadow p-4" style="max-width: 400px; width: 100%;">
            <h4 class="text-center mb-4">Registro</h4>

            <form id="frmRegister" action="" method="POST" autocomplete="off">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="username" minlength="5" placeholder="Nombre de Usuario" value="" required>
                    <label for="username"><i class="bi bi-person-fill"></i> Nombre de Usuario</label>
                    <p id="error-username" class="invalid-feedback"></p>
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" placeholder="name@example.com" value="" autocomplete="username" required>
                    <label for="email"><i class="bi bi-envelope-fill"></i> Correo electrónico</label>
                    <p id="error-email" class="invalid-feedback"></p>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" minlength="5" placeholder="Contraseña" value="" autocomplete="current-password" required>
                    <label for="password"><i class="bi bi-person-fill-lock"></i> Contraseña</label>
                    <p id="error-password" class="invalid-feedback"></p>
                </div>

                <div id="img_loader" class="mb-3" style="display:none;text-align:center;">
                    <img src="<?= BASE_URL ?>/public/assets/images/ajax-loader-blue.GIF" alt="Procesando...">
                </div>

                <div id="mensaje">
                    <!-- Aqui van los mensajes de error -->
                </div>

                <button class="btn btn-success w-100" type="submit">Registrar</button>
            </form>
        </div>
    </div>
</div>
<script>
    const base_url = "<?php echo BASE_URL; ?>";

    const form = document.getElementById("frmRegister");

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
                email.classList.remove("is-invalid");
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
        const reg_nombres = /^([a-zA-Z ñáéíóúÑÁÉÍÓÚ]{5,64})$/i;
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
        const data = new FormData();
        data.append("username", username.value);
        data.append("email", email.value);
        data.append("password", password.value);
        // Llamar al método auth/login que verifica si existe el email y clave
        try {
            let resp = await fetch("<?php echo BASE_URL ?>/auth/register", {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                body: data,
            });
            json = await resp.json();
            if (!json.error) {
                window.location = "<?php echo BASE_URL ?>/auth/dashboard";
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
                console.log(error);
                img_loader.style.display = "none";
                mensaje.innerHTML = error;
            }
        } catch (error) {
            console.log("Ocurrió un error: " + error)
        }
    }
</script>
<?php include './../resources/views/includes/footer.view.php' ?>
@extends('layout')

@section('contenido')
<div class="container">
    <h1>Crear contacto</h1>

    <div class="row">
        <div class="col-md-4">
            <form id="frmInsert" action="" method="post">
                <div class="form-group mb-3">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                    <p id="error-name" class="invalid-feedback"></p>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                    <p id="error-email" class="invalid-feedback"></p>
                </div>
                <div class="form-group mb-3">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" name="phone" id="phone" required>
                    <p id="error-phone" class="invalid-feedback"></p>
                </div>

                <div id="img_loader" class="mb-3" style="display:none;text-align:center;">
                    <img src="<?= BASE_URL ?>/public/assets/images/ajax-loader-blue.GIF" alt="Procesando...">
                </div>

                <div id="mensaje">
                    <!-- Aqui van los mensajes de error -->
                </div>

                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary w-100">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const base_url = "<?php echo BASE_URL; ?>";

    const form = document.getElementById("frmInsert");

    const name = document.getElementById("name");
    const email = document.getElementById("email");
    const phone = document.getElementById("phone");

    const mensaje = document.getElementById("mensaje");
    const img_loader = document.getElementById("img_loader");

    // Escuchar el evento submit
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío automático

        // Elimino algún mensaje de error previo
        document.querySelector("#mensaje").innerHTML = "";

        const name_value = name.value.trim();
        const email_value = email.value.trim();
        const phone_value = phone.value.trim();

        if (name_value == "" || email_value == "" || phone_value == "") {
            if (name_value == "") {
                name.classList.add("is-invalid");
                document.getElementById("error-name").innerHTML = "El campo Nombre es obligatorio.";
            } else {
                name.classList.remove("is-invalid");
                document.getElementById("error-name").innerHTML = "";
            }

            if (email_value == "") {
                email.classList.add("is-invalid");
                document.getElementById("error-email").innerHTML = "El campo Email es obligatorio.";
            } else {
                email.classList.remove("is-invalid");
                document.getElementById("error-email").innerHTML = "";
            }

            if (phone_value == "") {
                phone.classList.add("is-invalid");
                document.getElementById("error-phone").innerHTML = "El campo Phone es obligatorio.";
            } else {
                phone.classList.remove("is-invalid");
                document.getElementById("error-phone").innerHTML = "";
            }
        } else if (!validarEmail(email_value)) {
            email.classList.add("is-invalid");
            document.getElementById("error-email").innerHTML = "Por favor ingrese un correo electrónico válido.";
        } else if (name_value.length < 5) {
            name.classList.add("is-invalid");
            document.getElementById("error-name").innerHTML = "El campo Nombre debe tener al menos 5 caracteres.";
        } else if (phone_value.length < 7) {
            phone.classList.add("is-invalid");
            document.getElementById("error-phone").innerHTML = "El campo Phone debe tener al menos 5 caracteres.";
        } else if (!validarName(name_value)) {
            name.classList.add("is-invalid");
            document.getElementById("error-name").innerHTML = "Por favor ingrese solo caracteres alfabéticos entre 5 y 64 caracteres.";
        } else {
            registrar_contacto();
        }
    });

    function validarName(name) {
        const reg_nombres = /^([a-zA-Z ñáéíóúÑÁÉÍÓÚ]{5,64})$/i;
        return reg_nombres.test(name);
    }

    function validarEmail(email) {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return regex.test(email);
    }

    async function registrar_contacto() {
        // Eliminar todos los mensajes de error
        name.classList.remove("is-invalid");
        document.getElementById("error-name").innerHTML = "";
        email.classList.remove("is-invalid");
        document.getElementById("error-email").innerHTML = "";
        phone.classList.remove("is-invalid");
        document.getElementById("error-phone").innerHTML = "";
        // Desplegar el loader image
        document.querySelector("#img_loader").style.display = "block";
        // Obtener todos los campos a enviar mediante FormData
        const data = new FormData();
        data.append("name", name.value);
        data.append("email", email.value);
        data.append("phone", phone.value);
        // Llamar al método store del controlador UserController que inserta el nuevo usuario en la BD
        try {
            let resp = await fetch(base_url + "/contacts", {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                body: data,
            });
            json = await resp.json();
            if (!json.error) {
                window.location = base_url + "/contacts";
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
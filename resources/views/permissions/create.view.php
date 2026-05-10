@extends('layout')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Nuevo Permiso</h1>
            <a href="<?= BASE_URL ?>/users">Lista de Permisos</a>
            <h2>Ingresar nuevo Permiso</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
            <form id="frmInsert" action="" method="POST">
                <div class="form-group mb-3">
                    <label for="clave">Nombre del Permiso</label>
                    <input type="text" class="form-control" name="clave" id="clave" placeholder="Ingrese nuevo nombre de permiso" required>
                    <p id="error-clave" class="invalid-feedback"></p>
                </div>
                <div class="form-group mb-3">
                    <label for="descripcion">Descripción del Permiso</label>
                    <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Ingrese nuevo nombre de permiso" required>
                    <p id="error-descripcion" class="invalid-feedback"></p>
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

    const clave = document.getElementById("clave");
    const descripcion = document.getElementById("descripcion");

    const mensaje = document.getElementById("mensaje");
    const img_loader = document.getElementById("img_loader");

    // Escuchar el evento submit
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío automático

        // Elimino algún mensaje de error previo
        document.querySelector("#mensaje").innerHTML = "";

        const clave_value = clave.value.trim();
        const descripcion_value = descripcion.value.trim();

        if (clave_value == "" || descripcion_value == "") {
            if (clave_value == "") {
                clave.classList.add("is-invalid");
                document.getElementById("error-clave").innerHTML = "El campo Nombre del Permiso es obligatorio.";
            } else {
                clave.classList.remove("is-invalid");
                document.getElementById("error-clave").innerHTML = "";
            }

            if (descripcion_value == "") {
                descripcion.classList.add("is-invalid");
                document.getElementById("error-descripcion").innerHTML = "El campo Descripción del Permiso es obligatorio.";
            } else {
                clave.classList.remove("is-invalid");
                document.getElementById("error-descripcion").innerHTML = "";
            }
        } else if (clave_value.length < 5) {
            clave.classList.add("is-invalid");
            document.getElementById("error-clave").innerHTML = "El campo Nombre del Permiso debe tener al menos 5 caracteres.";
        } else if (descripcion_value.length < 5) {
            descripcion.classList.add("is-invalid");
            document.getElementById("error-descripcion").innerHTML = "El campo Descripción del Permiso debe tener al menos 5 caracteres.";
        } else if (!validarNombrePermiso(clave_value)) {
            clave.classList.add("is-invalid");
            document.getElementById("error-clave").innerHTML = "Por favor ingrese solo caracteres alfabéticos en minúsculas y guión entre 5 y 64 caracteres.";
        } else {
            registrar_permiso();
        }
    });

    function validarNombrePermiso(nombre) {
        const regex = /^[a-z]+(?:-[a-z]+)*$/;
        return regex.test(nombre);
    }

    async function registrar_permiso() {
        // Eliminar todos los mensajes de error
        clave.classList.remove("is-invalid");
        document.getElementById("error-clave").innerHTML = "";
        descripcion.classList.remove("is-invalid");
        document.getElementById("error-descripcion").innerHTML = "";
        // Desplegar el loader image
        document.querySelector("#img_loader").style.display = "block";
        // Obtener todos los campos a enviar mediante FormData
        const data = new FormData(form);
        // Llamar al método store del controlador PermissionController que inserta el nuevo permiso en la BD
        try {
            let resp = await fetch(base_url + "/permissions", {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                body: data,
            });
            json = await resp.json();
            if (!json.error) {
                window.location = base_url + "/permissions";
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

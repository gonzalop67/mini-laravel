@extends('layout')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Editar Permiso</h1>
            <a href="<?= BASE_URL ?>/permissions">Lista de Permisos</a>
            <h2>Editar Permiso: {{ $permiso['clave'] }}</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
            <form id="frmUpdate" action="" method="POST">
                <input type="text" name="id" id="id" value="{{ $permiso['id'] }}" hidden>
                <div class="form-group mb-3">
                    <label for="clave">Nombre del Permiso</label>
                    <input type="text" class="form-control" name="clave" id="clave" value="{{ $permiso['clave'] }}" placeholder="Ingrese nuevo nombre de permiso" required>
                    <p id="error-clave" class="invalid-feedback"></p>
                </div>
                <div class="form-group mb-3">
                    <label for="descripcion">Descripción del Permiso</label>
                    <input type="text" class="form-control" name="descripcion" id="descripcion" value="{{ $permiso['descripcion'] }}" placeholder="Ingrese nuevo nombre de permiso" required>
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

    const form = document.getElementById("frmUpdate");

    const id = document.getElementById("id");
    const clave = document.getElementById("clave");
    const descripcion = document.getElementById("descripcion");

    const mensaje = document.getElementById("mensaje");
    const img_loader = document.getElementById("img_loader");

    // Escuchar el evento submit
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío automático

        // Elimino algún mensaje de error previo
        document.querySelector("#mensaje").innerHTML = "";

        const id_value = id.value.trim();
        const clave_value = clave.value.trim();
        const descripcion_value = descripcion.value.trim();

        if (id_value == "" || clave_value == "" || descripcion_value == "") {
            if (id_value == "") {
                var error = '<div class="alert alert-danger" role="alert">' +
                    '<p><i class="bi bi-ban"></i> Existen errores:</p>' +
                    '<ul>' +
                    'No se ha pasado correctamente el id del permiso.' +
                    '</ul>' +
                    '</div>';
                mensaje.innerHTML = error;
            }

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
            actualizar_permiso();
        }
    });

    function validarNombrePermiso(nombre) {
        const regex = /^[a-z]+(?:-[a-z]+)*$/;
        return regex.test(nombre);
    }

    async function actualizar_permiso() {
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
            let resp = await fetch(base_url + "/permissions/" + id.value, {
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
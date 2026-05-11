@extends('layout')

@section('contenido')
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Editar Rol: {{ $rol['name'] }}</h1>
            <a href="<?= BASE_URL ?>/roles">Lista de Roles</a>
        </div>
    </div>
    <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
            <form id="frmUpdate" action="" method="POST">
                <input type="text" name="id" id="id" value="{{ $rol['id'] }}" hidden>
                <div class="form-group mb-3">
                    <label for="name">Nombre del Rol</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $rol['name'] }}" placeholder="Ingrese nombre del rol" required>
                    <p id="error-name" class="invalid-feedback"></p>
                </div>
                <div class="form-group mb-3">
                    <label for="slug">Slug del Rol</label>
                    <input type="text" class="form-control" name="slug" id="slug" value="{{ $rol['slug'] }}" placeholder="Ingrese slug del rol" required>
                    <p id="error-slug" class="invalid-feedback"></p>
                </div>
                <div class="form-group mb-3">
                    <label for="description">Descripción del Rol</label>
                    <input type="text" class="form-control" name="description" value="{{ $rol['description'] }}" id="description" placeholder="Ingrese descripción del rol" required>
                    <p id="error-description" class="invalid-feedback"></p>
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
    const name = document.getElementById("name");
    const inputSlug = document.getElementById("slug");
    const description = document.getElementById("description");

    const mensaje = document.getElementById("mensaje");
    const img_loader = document.getElementById("img_loader");

    const generarSlug = () => {
        nombre = name.value;
        // 1. Eliminar espacios al inicio y final
        let slug = nombre.trim();

        // 2. Convertir a minúsculas
        slug = slug.toLowerCase();

        // 3. Eliminar acentos y caracteres especiales mapeando
        slug = slug.replace(/[àáäâèéëêìíïîòóöôùúüûñç]/g, function(match) {
            return {
                à: "a",
                á: "a",
                ä: "a",
                â: "a",
                è: "e",
                é: "e",
                ë: "e",
                ê: "e",
                ì: "i",
                í: "i",
                ï: "i",
                î: "i",
                ò: "o",
                ó: "o",
                ö: "o",
                ô: "o",
                ù: "u",
                ú: "u",
                ü: "u",
                û: "u",
                ñ: "n",
                ç: "c",
            } [match];
        });

        // 4. Reemplazar caracteres no permitidos (letras, números, guiones y espacios) por un guion
        slug = slug.replace(/[^a-z0-9 -]/g, "");

        // 5. Reemplazar espacios múltiples y guiones por un solo guion
        slug = slug.replace(/[\s-]+/g, "-");

        // 6. Eliminar guiones al inicio o al final
        slug = slug.replace(/^-+|-+$/g, "");

        inputSlug.value = slug;
    };

    name.addEventListener("blur", generarSlug);
    name.addEventListener("keyup", generarSlug);

    // Escuchar el evento submit
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Evita el envío automático

        // Elimino algún mensaje de error previo
        document.querySelector("#mensaje").innerHTML = "";

        const name_value = name.value.trim();
        const slug_value = inputSlug.value.trim();
        const description_value = description.value.trim();

        if (name_value == "" || slug_value == "" || description_value == "") {
            if (name_value == "") {
                name.classList.add("is-invalid");
                document.getElementById("error-name").innerHTML = "El campo Nombre del Permiso es obligatorio.";
            } else {
                name.classList.remove("is-invalid");
                document.getElementById("error-name").innerHTML = "";
            }

            if (slug_value == "") {
                inputSlug.classList.add("is-invalid");
                document.getElementById("error-slug").innerHTML = "El campo Slug del Rol es obligatorio.";
            } else {
                inputSlug.classList.remove("is-invalid");
                document.getElementById("error-slug").innerHTML = "";
            }

            if (description_value == "") {
                description.classList.add("is-invalid");
                document.getElementById("error-description").innerHTML = "El campo Descripción del Permiso es obligatorio.";
            } else {
                name.classList.remove("is-invalid");
                document.getElementById("error-description").innerHTML = "";
            }
        } else if (description_value.length < 5) {
            description.classList.add("is-invalid");
            document.getElementById("error-description").innerHTML = "El campo Descripción del Permiso debe tener al menos 5 caracteres.";
        } else if (!validarRol(name_value)) {
            name.classList.add("is-invalid");
            document.getElementById("error-name").innerHTML = "Por favor ingrese solo caracteres alfanuméricos, guión bajo y guión entre 3 y 30 caracteres.";
        } else {
            actualizar_rol();
        }
    });

    function validarRol(rol) {
        const regex = /^[A-Za-z0-9_-]{3,30}$/;
        return regex.test(rol);
    }

    async function actualizar_rol() {
        // Eliminar todos los mensajes de error
        name.classList.remove("is-invalid");
        document.getElementById("error-name").innerHTML = "";
        inputSlug.classList.remove("is-invalid");
        document.getElementById("error-slug").innerHTML = "";
        description.classList.remove("is-invalid");
        document.getElementById("error-description").innerHTML = "";
        // Desplegar el loader image
        document.querySelector("#img_loader").style.display = "block";
        // Obtener todos los campos a enviar mediante FormData
        const data = new FormData(form);
        // Llamar al método store del controlador PermissionController que inserta el nuevo permiso en la BD
        try {
            let resp = await fetch(base_url + "/roles/" + id.value, {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                body: data,
            });
            json = await resp.json();
            if (!json.error) {
                window.location = base_url + "/roles";
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
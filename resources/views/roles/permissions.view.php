@extends('layout')

@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h3 class="mt-3">Asignar Permisos a: {{ $rol['name'] }}</h1>
                    <a href="{{ BASE_URL }}/roles">Lista de Roles</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form action="{{ BASE_URL }}/roles/{{ $rol['id'] }}/permissions" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" id="select_all" class="form-check-input">
                            <label for="select_all" class="form-check-label"><strong>Seleccionar Todos</strong></label>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission['id'] }}"
                                        id="perm_{{ $permission['id'] }}" class="form-check-input"
                                        {{ in_array($permission['id'], $rolePermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission['id'] }}">
                                        {{ $permission['name'] }} ({{ $permission['slug'] }})
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        let selectAllCheckbox = document.getElementById("select_all");
        selectAllCheckbox.addEventListener("change", function() {
            const checkboxes = document.querySelectorAll("input[name=\"permissions[]\"]");
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
@endsection

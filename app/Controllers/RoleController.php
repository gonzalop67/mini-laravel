<?php

namespace App\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;

class RoleController extends Controller
{
    protected Role $roleModel;
    protected User $userModel;
    protected Permission $permissionModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->roleModel = new Role;
        $this->userModel = new User;
        $this->permissionModel = new Permission;
    }

    public function index()
    {
        $roles = $this->roleModel->all();
        $title = "Lista de Roles";
        return $this->view('roles.index', compact('roles', 'title'));
    }

    public function create()
    {
        $title = "Nuevo Rol";
        return $this->view('roles.create', compact('title'));
    }

    public function store()
    {
        if ($this->roleModel->validate($_POST)) {
            $rol = $this->roleModel->create($_POST);
            if (empty($rol)) {
                return json_encode([
                    'error' => true,
                    'errors' => [
                        'db_error' => 'No se pudo insertar el rol en la base de datos'
                    ]
                ]);
            } else {
                return json_encode([
                    'error' => false
                ]);
            }
        }

        return json_encode([
            'error' => true,
            'errors' => $this->roleModel->errors
        ]);
    }

    public function edit(int $id)
    {
        $title = "Actualizar Rol";
        $rol = $this->roleModel->find($id);

        return $this->view('roles.edit', compact('title', 'rol'));
    }

    public function update(int $id)
    {
        if ($this->roleModel->validate($_POST)) {
            $contact = $this->roleModel->update($id, $_POST);
            if (empty($contact)) {
                return json_encode([
                    'error' => true,
                    'errors' => [
                        'db_error' => 'No se pudo actualizar el rol en la base de datos'
                    ]
                ]);
            } else {
                return json_encode([
                    'error' => false
                ]);
            }
        }

        return json_encode([
            'error' => true,
            'errors' => $this->roleModel->errors
        ]);
    }

    public function permissions(int $id)
    {
        // 1. El rol que estamos editando
        $rol = $this->roleModel->find($id);

        // 2. TODOS los permisos que existen en el sistema (para los checkboxes)
        // Asumo que tienes un permissionModel o tabla 'permissions'
        $permissions = $this->permissionModel->all();

        // 3. Los IDs de los permisos que este ROL ya tiene asignados
        // Esta es la simulación real de: $role->permissions->pluck('id')->toArray();
        $rolePermissions = $this->roleModel->getPermissionIds($id);

        return $this->view('roles.permissions', compact('rol', 'permissions', 'rolePermissions'));
    }
}

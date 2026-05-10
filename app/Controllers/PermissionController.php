<?php

namespace App\Controllers;

use App\Models\Permission;

class PermissionController extends Controller
{
    protected Permission $permissionModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->permissionModel = new Permission;
    }

    public function index()
    {
        $permissions = $this->permissionModel->all();
        $title = "Lista de Permisos";
        return $this->view('permissions.index', compact('permissions', 'title'));
    }

    public function create()
    {
        $title = "Nuevo Permiso";
        return $this->view('permissions.create', compact('title'));
    }

    public function store()
    {
        if ($this->permissionModel->validate($_POST)) {
            $permiso = $this->permissionModel->create($_POST);
            if (empty($permiso)) {
                return json_encode([
                    'error' => true,
                    'errors' => [
                        'db_error' => 'No se pudo insertar el permiso en la base de datos'
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
            'errors' => $this->permissionModel->errors
        ]);
    }

    public function edit(int $id)
    {
        $title = "Actualizar Permiso";
        $permiso = $this->permissionModel->find($id);

        return $this->view('permissions.edit', compact('title', 'permiso'));
    }

    public function update(int $id)
    {
        if ($this->permissionModel->validate($_POST)) {
            $contact = $this->permissionModel->update($id, $_POST);
            // show($contact);
            // die();
            if (empty($contact)) {
                return json_encode([
                    'error' => true,
                    'errors' => [
                        'db_error' => 'No se pudo actualizar el permiso en la base de datos'
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
            'errors' => $this->permissionModel->errors
        ]);
    }
}
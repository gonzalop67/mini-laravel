<?php

namespace App\Controllers;

use App\Models\Role;

class RoleController extends Controller
{
    protected Role $roleModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->roleModel = new Role;
    }

    public function index()
    {
        $roles = $this->roleModel->all();
        $title = "Lista de Roles";
        return $this->view('roles.index', compact('roles', 'title'));
    }
}
<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends Controller
{
    protected User $userModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->userModel = new User;
    }

    public function index()
    {
        $users = $this->userModel->all();
        $title = "Lista de Usuarios";
        return $this->view('users.index', compact('users', 'title'));
    }

    public function create()
    {
        $title = "Nuevo Usuario";
        return $this->view('users.create', compact('title'));
    }

    public function store()
    {
        if ($this->userModel->validate($_POST)) {
            $password = $_POST['password'];
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            $_POST['password'] = $hash_password;
            $usuario = $this->userModel->create($_POST);
            if (empty($usuario)) {
                return json_encode([
                    'error' => true,
                    'errors' => [
                        'db_error' => 'No se pudo insertar el usuario en la base de datos'
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
            'errors' => $this->userModel->errors
        ]);
    }

    public function edit($id)
    {
        //
    }
}

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
        $password = $_POST['password'];
        $hash_password = password_hash($password, PASSWORD_DEFAULT);
        $_POST['password'] = $hash_password;
        $this->userModel->create($_POST);
        header('Location: ' . BASE_URL . '/users');
    }

    public function edit($id)
    {
        //
    }
}

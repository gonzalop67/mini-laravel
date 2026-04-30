<?php

namespace App\Controllers;

use App\Models\User;

class LoginController extends Controller
{
    protected User $userModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->userModel = new User;
    }

    public function showLoginForm()
    {
        $title = "Login Form";
        return $this->view('auth.login', compact('title'));
    }

    public function showregisterForm()
    {
        $title = "Register Form";
        return $this->view('auth.register', compact('title'));
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $usuario = $this->userModel->where('email', $email)->first();

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $usuario['username'];
            return json_encode([
                'error' => false,
                'usuario' => $usuario
            ]);
        } else {
            return json_encode([
                'error' => true,
                'mensaje' => "Usuario o password incorrectos."
            ]);
        }
    }

    public function register()
    {
        if ($this->userModel->validate($_POST)) {

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $data = [
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword
            ];

            if ($usuario = $this->userModel->create($data)) {
                $_SESSION['authenticated'] = true;
                $_SESSION['username'] = $usuario['username'];
                return json_encode([
                    'error' => false,
                    'authenticated' => true
                ]);
            }
        }

        return json_encode([
            'error' => true,
            'authenticated' => false,
            'errors' => $this->userModel->errors
        ]);
    }

    public function dashboard()
    {
        $title = "Main Dashboard";
        return $this->view('home', compact('title'));
    }

    public function logout()
    {
        session_start(); // Unirse a la sesión actual
        session_unset(); // Limpiar las variables de sesión
        session_destroy(); // Destruir la sesión en el servidor

        redireccionar('/');
    }
}

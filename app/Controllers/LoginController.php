<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\RolePermission;

class LoginController extends Controller
{
    protected User $userModel;
    protected Role $roleModel;
    protected RoleUser $roleUserModel;
    protected RolePermission $rolePermissionModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->userModel = new User;
        $this->roleModel = new Role;
        $this->roleUserModel = new RoleUser;
        $this->rolePermissionModel = new RolePermission;
    }

    public function showLoginForm()
    {
        $title = "Login Form";
        $roles = $this->roleModel->orderBy('name')->get();
        return $this->view('auth.login', compact('title', 'roles'));
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
        $rol_id = $_POST['rol_id'];

        $usuario = $this->userModel->where('email', $email)->first();

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Verificar si el perfil ingresado pertenece al usuario
            $usuario_id = $usuario['id'];

            $rolUsuario = $this->roleUserModel
                ->where('usuario_id', $usuario_id)
                ->where('rol_id', $rol_id)
                ->first();

            if (!empty($rolUsuario)) {
                // ASEGÚRATE DE QUE session_start() se ejecutó antes
                if (session_status() === PHP_SESSION_NONE) session_start();

                $_SESSION['authenticated'] = true;
                $_SESSION['username'] = $usuario['username'];

                $permissions = $this->rolePermissionModel->getPermissionsByRoleId($rol_id);

                // $_SESSION['permisos'] = [];

                // foreach ($permissions as $key => $value) {
                //     $_SESSION['permisos'][] = $value['slug'];
                // }

                $_SESSION['permisos'] = array_column($permissions, 'slug');

                return json_encode([
                    'error' => false,
                    'usuario' => $usuario
                ]);
            } else {
                return json_encode([
                    'error' => true,
                    'mensaje' => 'El Usuario no tiene asignado el rol seleccionado.' // Quité el nivel "errors"
                ]);
            }
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = []; // Forma más segura de limpiar que session_unset()
        session_destroy();

        // Borrar la cookie de sesión del navegador (esto evita el "limbo" al reingresar)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        redireccionar('/');
        exit();
    }
}

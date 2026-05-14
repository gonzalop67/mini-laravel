<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;

class UserController extends Controller
{
    protected User $userModel;
    protected Role $roleModel;
    protected RoleUser $roleUserModel;

    public function __construct()
    {
        parent::__construct(); // <--- ESTO ES OBLIGATORIO
        $this->userModel = new User;
        $this->roleModel = new Role;
        $this->roleUserModel = new RoleUser;
    }

    public function index()
    {
        $title = "Lista de Usuarios";

        $search = isset($_GET['search']) ? $_GET['search'] : "";

        if ($search !== "") {
            $users = $this->userModel->where('username', 'LIKE', '%' . $_GET['search'] . '%')->paginate(5);
        } else {
            $users = $this->userModel->paginate(5);
        }

        return $this->view('users.index', compact('users', 'title'));
    }

    public function show(int $id)
    {
        $title = "Detalle del Contacto";
        $contact = $this->userModel->find($id);

        return $this->view('users.show', compact('title', 'contact'));
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

    public function roles(int $id)
    {
        // 1. El usuario que estamos editando
        $user = $this->userModel->find($id);

        // 2. TODOS los roles que existen en el sistema (para los checkboxes)
        // Asumo que tienes un roleModel o tabla 'roles'
        $roles = $this->roleModel->all();

        // 3. Los IDs de los roles que este Usuario ya tiene asignados
        // Esta es la simulación real de: $user->roles->pluck('id')->toArray();
        $userRoles = $this->userModel->getRoleIds($id);

        return $this->view('users.roles', compact('user', 'roles', 'userRoles'));
    }

    public function updateRoles(int $id)
    {
        // $id es el id del usuario
        $RoleIds = $_POST['roles'];
        $this->roleUserModel->sync($id, $RoleIds);

        // Mensaje de éxito
        $_SESSION['mensaje'] = "Roles actualizados satisfactoriamente.";
        $_SESSION['tipo'] = "success";
        $_SESSION['icono'] = "check";
        redireccionar('/users');
    }

    public function edit($id)
    {
        //
    }
}

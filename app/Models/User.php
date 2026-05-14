<?php

namespace App\Models;

class User extends Model
{
    protected string $table = 'usuarios';
    protected array $fillable = ['username', 'email', 'password', 'status', 'updated_at'];

    public function validate(array $data)
    {
        $this->errors = [];

        if (empty($data['username'])) {
            $this->errors['username'] = "Username is required";
        } else if ($this->exists('username', $data['username'])) {
            $this->errors['username'] = "Ya existe el nombre de usuario";
        }

        if (empty($data['email'])) {
            $this->errors['email'] = "Email is required";
        } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Email is not valid";
        } else if ($this->exists('email', $data['email'])) {
            $this->errors['email'] = "Ya existe el correo electrónico";
        }

        if (empty($data['password'])) {
            $this->errors['password'] = "Password is required";
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function getRoleIds(string $userId)
    {
        $sql = "SELECT rol_id FROM usuario_rol WHERE usuario_id = ?";
        $data = $this->query($sql, [$userId])->get();

        // Aquí es donde simulamos el pluck('id')->toArray()
        return array_column($data, 'rol_id');
    }
}

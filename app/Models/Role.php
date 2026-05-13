<?php

namespace App\Models;

class Role extends Model
{
    protected string $table = 'roles';
    protected array $fillable = ['name', 'slug', 'description'];

    public function validate(array $data)
    {
        $this->errors = [];

        if (empty($data['name'])) {
            $this->errors['name'] = "El campo Nombre del Rol es obligatorio";
        } else {
            if (isset($_POST['id'])) {
                $rol = $this->find($_POST['id']);
                if ($rol['name'] !== $data['name'] && $this->exists('name', $data['name'])) {
                    $this->errors['name'] = "Ya existe el nombre del rol";
                }
            } else {
                if ($this->exists('name', $data['name'])) {
                    $this->errors['name'] = "Ya existe el nombre del rol";
                }
            }
        }

        if (empty($data['slug'])) {
            $this->errors['slug'] = "El campo Slug del Rol es obligatorio";
        } else {
            if (isset($_POST['id'])) {
                $rol = $this->find($_POST['id']);
                if ($rol['slug'] !== $data['slug'] && $this->exists('slug', $data['slug'])) {
                    $this->errors['slug'] = "Ya existe el nombre del rol";
                }
            } else {
                if ($this->exists('slug', $data['slug'])) {
                    $this->errors['slug'] = "Ya existe el slug del rol";
                }
            }
        }

        if (empty($data['description'])) {
            $this->errors['description'] = "El campo Descripción del Rol es obligatorio";
        } else {
            if (isset($_POST['id'])) {
                $rol = $this->find($_POST['id']);
                if ($rol['description'] !== $data['description'] && $this->exists('description', $data['description'])) {
                    $this->errors['description'] = "Ya existe la descripción del rol";
                }
            } else {
                if ($this->exists('description', $data['description'])) {
                    $this->errors['description'] = "Ya existe la descripción del rol";
                }
            }
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    public function getPermissionIds(string $roleId)
    {
        $sql = "SELECT permiso_id FROM rol_permiso WHERE rol_id = ?";
        $data = $this->query($sql, [$roleId])->get();

        // Aquí es donde simulamos el pluck('id')->toArray()
        return array_column($data, 'permiso_id');
    }
}

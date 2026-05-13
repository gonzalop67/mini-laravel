<?php

namespace App\Models;

class Permission extends Model
{
    protected string $table = 'permisos';
    protected array $fillable = ['name', 'slug', 'descripcion'];

    public function validate(array $data)
    {
        $this->errors = [];

        if (empty($data['name'])) {
            $this->errors['name'] = "El campo Nombre del Permiso es obligatorio";
        } else {
            if (isset($_POST['id'])) {
                $permiso = $this->find($_POST['id']);
                if ($permiso['name'] !== $data['name'] && $this->exists('name', $data['name'])) {
                    $this->errors['name'] = "Ya existe el nombre del permiso";
                }
            } else {
                if ($this->exists('name', $data['name'])) {
                    $this->errors['name'] = "Ya existe el nombre del permiso";
                }
            }
        }

        if (empty($data['slug'])) {
            $this->errors['slug'] = "El campo Slug del Permiso es obligatorio";
        } else {
            if (isset($_POST['id'])) {
                $permiso = $this->find($_POST['id']);
                if ($permiso['slug'] !== $data['slug'] && $this->exists('slug', $data['slug'])) {
                    $this->errors['slug'] = "Ya existe el nombre del permiso";
                }
            } else {
                if ($this->exists('slug', $data['slug'])) {
                    $this->errors['slug'] = "Ya existe el slug del permiso";
                }
            }
        }

        if (empty($data['descripcion'])) {
            $this->errors['descripcion'] = "El campo Descripción del Permiso es obligatorio";
        } else {
            if (isset($_POST['id'])) {
                $permiso = $this->find($_POST['id']);
                if ($permiso['descripcion'] !== $data['descripcion'] && $this->exists('descripcion', $data['descripcion'])) {
                    $this->errors['descripcion'] = "Ya existe la descripción del permiso";
                }
            } else {
                if ($this->exists('descripcion', $data['descripcion'])) {
                    $this->errors['descripcion'] = "Ya existe la descripción del permiso";
                }
            }
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }
}

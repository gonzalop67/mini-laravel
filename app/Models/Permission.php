<?php

namespace App\Models;

class Permission extends Model
{
    protected string $table = 'permisos';
    protected array $fillable = ['clave', 'descripcion'];

    public function validate(array $data)
    {
        $this->errors = [];

        if (empty($data['clave'])) {
            $this->errors['clave'] = "El campo Nombre del Permiso es obligatorio";
        } else {
            if (isset($_POST['id'])) {
                $permiso = $this->find($_POST['id']);
                if ($permiso['clave'] !== $data['clave'] && $this->exists('clave', $data['clave'])) {
                    $this->errors['clave'] = "Ya existe el nombre del permiso";
                }
            } else {
                if ($this->exists('clave', $data['clave'])) {
                    $this->errors['clave'] = "Ya existe el nombre del permiso";
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

<?php

namespace App\Models;

class RolePermission extends Model
{
    protected string $table = 'rol_permiso';
    protected array $fillable = ['rol_id', 'permiso_id'];

    public function sync(int $id, array $permissionIds)
    {
        $sql = "DELETE FROM {$this->table} WHERE rol_id = ?";
        $this->query($sql, [$id], 'i');

        for ($i = 0; $i < count($permissionIds); $i++) {
            //Insertar en la tabla rol_permiso
            $sql = "INSERT INTO {$this->table} (rol_id, permiso_id) VALUES (?, ?)";
            $this->query($sql, [$id, $permissionIds[$i]], 'ii');
        }
    }

    public function getPermissionsByRoleId(int $rol_id)
    {
        // Recuperar los permisos según el rol
        $sql = "SELECT p.slug FROM permisos p  
                INNER JOIN rol_permiso rp ON rp.permiso_id = p.id 
                WHERE rp.rol_id = ?";

        $query = $this->query($sql, [$rol_id], 'i');

        return $query->get();

    }
}

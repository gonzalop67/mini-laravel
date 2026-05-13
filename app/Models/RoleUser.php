<?php

namespace App\Models;

class RoleUser extends Model
{
    protected string $table = 'usuario_rol';
    protected array $fillable = ['usuario_id', 'rol_id'];

    public function sync(int $id, array $rolesIds)
    {
        $sql = "DELETE FROM {$this->table} WHERE usuario_id = ?";
        $this->query($sql, [$id], 'i');

        for ($i = 0; $i < count($rolesIds); $i++) {
            //Insertar en la tabla usuario_rol
            $sql = "INSERT INTO {$this->table} (usuario_id, rol_id) VALUES (?, ?)";
            $this->query($sql, [$id, $rolesIds[$i]], 'ii');
        }
    }

    public function getRolesByUserId(int $user_id)
    {
        // Recuperar los roles según el user_id
        $sql = "SELECT r.slug FROM roles r  
                INNER JOIN usuario_rol ur ON ur.rol_id = r.id 
                WHERE ur.usuario_id = ?";

        $query = $this->query($sql, [$user_id], 'i');

        show($query);
        die();

    }
}

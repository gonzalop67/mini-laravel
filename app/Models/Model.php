<?php

namespace App\Models;

use mysqli;

class Model
{
    protected string $table;
    protected array $fillable = [];
    protected mysqli $db;

    protected string $db_host = DB_HOST;
    protected string $db_user = DB_USER;
    protected string $db_pass = DB_PASS;
    protected string $db_name = DB_NAME;

    protected mysqli $connection;
    protected mixed $query = null;

    public array $errors      = [];

    public function __construct()
    {
        $this->connection();
    }

    public function connection()
    {
        $this->connection = new mysqli($this->db_host, $this->db_user, $this->db_pass, $this->db_name);

        if ($this->connection->connect_error) {
            die('Error de conexión: ' . $this->connection->connect_error);
        }
    }

    public function query(string $sql)
    {
        $this->query = $this->connection->query($sql);

        return $this;
    }

    public function first()
    {
        return $this->query->fetch_assoc();
    }

    public function get()
    {
        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    // Consultas
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->get();
    }

    public function find(int $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id}";
        return $this->query($sql)->first();
    }

    public function where(string $column, string $operator, string|int|null $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = "=";
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} '{$value}'";
        $this->query($sql);

        return $this;
    }

    public function exists(string $column, string $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = '{$value}'";
        $result = $this->query($sql)->get();
        return count($result) > 0;
    }

    public function create(array $data)
    {
        // Remove unwanted data
        if (!empty($this->fillable)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->fillable)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $keys);

        $sql = rtrim($sql, ", ");
        $sql .= ") VALUES(";

        foreach ($data as $key => $value) {
            $sql .= "'" . $value . "', ";
        }

        $sql = rtrim($sql, ", ");
        $sql .= ")";

        $this->query($sql);

        $insert_id = $this->connection->insert_id;

        return $this->find($insert_id);
    }

    public function update(int $id, array $data)
    {
        $fields = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $fields[] = "{$key} = ?";
            }
        }

        $fields = implode(', ', $fields);

        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = {$id}";

        $this->query($sql);

        return $this->find($id);
    }
}

<?php

namespace App\Models;

class Contact extends Model
{
    protected string $table = 'contacts';
    protected array $fillable = ['name', 'email', 'phone'];

    public function validate(array $data)
    {
        $this->errors = [];

        if (empty($data['name'])) {
            $this->errors['name'] = "El campo Nombre es obligatorio";
        }

        if (empty($data['email'])) {
            $this->errors['email'] = "El campo Email es obligatorio";
        } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "El email introducido no es válido";
        }

        if (empty($data['phone'])) {
            $this->errors['phone'] = "El campo Teléfono es obligatorio";
        }

        return empty($this->errors);
    }
}

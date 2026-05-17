<?php

namespace App\Models;

class Institution extends Model
{
    protected string $table = 'institucion';
    protected array $fillable = ['nombre', 'url'];

}
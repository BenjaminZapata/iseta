<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'rol',
        'email'
    ];

    public $timestamps = false;

    protected $guard = "admin";

    protected $table = "administradores";

    public function rol()
    {
        $rol = [
            0 => 'Regente',
            1 => 'Preceptor',
            2 => 'Secretario'
        ];
        return $rol[$this->rol] ?? 'Desconocido';
    }
}

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
        'rol'
    ];

    public $timestamps = false;

    protected $guard = "admin";

    protected $table = "administradores";

    public function rol()
    {
        $rol = [
            0 => 'regente',
            1 => 'preceptor',
            2 => 'secretario'
        ];
        return $rol[$this->rol] ?? 'Desconocido';
    }
}

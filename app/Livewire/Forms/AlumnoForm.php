<?php

namespace App\Livewire\Forms;

use App\Models\Alumno;
use App\Rules\Telefono;
use Livewire\Form;

class AlumnoForm extends Form
{

    public $dni;
    public $nombre;
    public $apellido;
    public $fecha_nacimiento;
    public $ciudad;
    public $calle;
    public $ciudad_nacimiento;
    public $dpto;
    public $piso;
    public $estado_civil;
    public $email;
    public $nombre_institucion_secundario;
    public $titulo_anterior;
    public $becas;
    public $observaciones;
    public $telefono_1;
    public $telefono_2;
    public $telefono_3;
    public $codigo_postal;
    public $titulo_secundario;
    public $lugar_nacimiento;

    public function rules()
    {
        return [
            'dni' => 'required|numeric|max_digits:8|unique:alumnos,dni',
            'nombre' => ['required', 'string', 'max:30'],
            'apellido' => ['required', 'string', 'max:30'],
            'fecha_nacimiento' => ['required', 'date', 'before:now'],
            'ciudad' => ['nullable', 'string', 'max:30'],
            'calle' => ['nullable', 'string', 'max:30'],
            'ciudad_nacimiento' => ['nullable', 'string', 'max:30'],
            'dpto' => ['nullable', 'string', 'max:5'],
            'piso' => ['nullable', 'integer', 'between:0,15'],
            'estado_civil' => ['required', 'integer', 'between:0,5'],
            'email' => ['nullable', 'email', 'max:50'],
            'nombre_institucion_secundario' => ['nullable', 'string', 'max:255',],
            'titulo_anterior' => ['nullable', 'string', 'max:255'],
            'becas' => ['nullable', 'integer', 'between:0,9'],
            'observaciones' => ['nullable'],
            'telefono_1' => ['required', new Telefono],
            'telefono_2' => ['nullable', new Telefono],
            'telefono_3' => ['nullable', new Telefono],
            'codigo_postal' => ['nullable', 'alpha_num', 'max:10'],
            'titulo_secundario' => ['required', 'integer', 'between:0,4'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:30'],
            // luar de nacimiento, determinar si contiene espacios, limite de caracteres y ademas copiarlo tal cual en EditarAlumnooRequest.
        ];
    }


    public function messages()
    {
        return [
            'dni.unique' => 'Ya hay un alumno con ese DNI.',
            'dni.min_digits' => 'El campo debe tener al menos 7 dígitos.',
            'dni.required' => 'El campo DNI es obligatorio.',
            'fecha_nacimiento.before' => 'El campo debe ser menor que la fecha actual.',
        ];
    }
    public function validateAlumnos(): array
    {
        return $this->validate();
    }
}

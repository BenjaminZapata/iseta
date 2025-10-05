<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Profesor;
use App\Models\Carrera;

class ProfesorVinculacion extends Component
{
    public Profesor $profesor;
    public $carreras;
    public $carrerasSeleccionadas = [];
    public $asignaturasSeleccionadas = [];

    public function mount(Profesor $profesor)
    {
        $this->profesor = $profesor;
        $this->carreras = Carrera::with('asignaturas')->get();

        // Inicializar asignaturasSeleccionadas con las asignaturas ya vinculadas
        $this->asignaturasSeleccionadas = [];

        foreach ($profesor->asignaturas as $asig) {
            $pivot = $asig->pivot; // datos extra en tabla pivote
            $this->asignaturasSeleccionadas[$asig->id] = [
                'checked' => true,
                'id_carrera' => $pivot->id_carrera,
                'anio' => $pivot->anio,
                'tipo_modulo' => $pivot->tipo_modulo,
                // Agrega más campos si necesitas
            ];
        }

        // También llenar carrerasSeleccionadas para que se muestren
        $this->carrerasSeleccionadas = $this->profesor->asignaturas->pluck('pivot.id_carrera')->unique()->toArray();
    }

    public function getCarrerasConAsignaturasProperty()
    {
        return Carrera::with('asignaturas')
            ->whereIn('id', $this->carrerasSeleccionadas)
            ->get();
    }

    public function pruebaEmit()
    {
        $this->emit('eventoDePrueba');
    }

    public function guardarVinculaciones()
    {
        $datosSync = [];

        foreach ($this->asignaturasSeleccionadas as $idAsignatura => $datos) {
            if (!empty($datos['checked'])) {
                // Buscar carrera asociada a la asignatura
                $idCarrera = $datos['id_carrera'] ?? $this->buscarIdCarreraPorAsignatura($idAsignatura);

                $datosSync[$idAsignatura] = [
                    'id_carrera' => $idCarrera,
                    'anio' => $datos['anio'] ?? null,
                    'tipo_modulo' => $datos['tipo_modulo'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $this->profesor->asignaturas()->sync($datosSync);


        $this->emit('asignaturasActualizadas');
        session()->flash('success', 'Asignaturas guardadas correctamente.');
    }

    private function buscarIdCarreraPorAsignatura($idAsignatura)
    {
        foreach ($this->carreras as $carrera) {
            if ($carrera->asignaturas->contains('id', $idAsignatura)) {
                return $carrera->id;
            }
        }
        return null;
    }

    public function render()
    {
        return view('livewire.admin.profesor-vinculacion');
    }
}

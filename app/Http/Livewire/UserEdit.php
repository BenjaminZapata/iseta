<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Traits\GeneratesBreadcrumbs;

class UserEdit extends Component
{
 use GeneratesBreadcrumbs;

 public array $breadcrumbs = [];

 public function mount($userId)
 {
  $this->breadcrumbs = $this->generateBreadcrumbs([
   ['label' => 'Inicio', 'route' => 'home'],
   ['label' => 'Usuarios', 'route' => 'users.index'],
   ['label' => 'Editar usuario', 'route' => 'users.edit', 'params' => ['user' => $userId]],
  ]);
 }

 public function render()
 {
  return view('livewire.user-edit', [
   'breadcrumbs' => $this->breadcrumbs,
  ]);
 }
}

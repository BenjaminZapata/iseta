<?php

namespace App\Traits;

trait GeneratesBreadcrumbs
{
 public function generateBreadcrumbs(array $segments): array
 {
  $breadcrumbs = [];
  foreach ($segments as $segment) {
   $breadcrumbs[] = [
    'label' => $segment['label'],
    'url' => route($segment['route'], $segment['params'] ?? []),
   ];
  }
  return $breadcrumbs;
 }
}

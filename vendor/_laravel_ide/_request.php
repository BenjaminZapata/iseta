<?php

namespace Illuminate\Http;

interface Request
{
    /**
     * @return \App\Models\Alumno|null
     */
    public function user($guard = null);
}
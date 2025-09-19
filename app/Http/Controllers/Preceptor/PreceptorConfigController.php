<?php

namespace App\Http\Controllers\Preceptor;
use App\Http\Controllers\Controller;

use App\Http\Requests\EditarConfigRequest;
use App\Models\Configuracion;
use Illuminate\Http\Request;


class PreceptorConfigController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index()
    {
        $configuracion = Configuracion::all()->pluck('value', 'key')->toArray();
        return view('preceptor/config', compact('configuracion'));
    }

    public function setear(EditarConfigRequest $request)
    {
        $data = $request->validated();

        if (!$request->has('alumno_puede_anotarse_mesa')) {
            $data['alumno_puede_anotarse_mesa'] = 0;
        }
        if (!$request->has('alumno_puede_bajarse_mesa')) {
            $data['alumno_puede_bajarse_mesa'] = 0;
        }
        if (!$request->has('alumno_puede_anotarse_cursada')) {
            $data['alumno_puede_anotarse_cursada'] = 0;
        }
        if (!$request->has('alumno_puede_bajarse_cursada')) {
            $data['alumno_puede_bajarse_cursada'] = 0;
        }
        if (!$request->has('alumno_puede_anotarse_libre')) {
            $data['alumno_puede_anotarse_libre'] = 0;
        }
        if (!$request->has('modo_seguro')) {
            $data['modo_seguro'] = 0;
        }


        foreach ($data as $key => $value) {
            if (!$value)
                $value = '';
            Configuracion::where('key', $key)
                ->update(['value' => $value]);
        }

        return redirect()->back();
    }

    public function setOnly(Request $request)
    {
        $key = key($request->except('_token'));


        Configuracion::where('key', $key)
            ->update(['value' => $request->input($key)]);

        // key($request->except('_token'));

        return redirect()->back();
    }

    function modoSeguro()
    {

        $modo_seguro = Configuracion::where('key', 'modo_seguro')->first();


        if ($modo_seguro->value == 1) {
            $modo_seguro->value = 0;
            $msj = 'Se ha desactivado el modo seguro.';
        } else {
            $modo_seguro->value = 1;
            $msj = 'Se ha activado el modo seguro.';
        }

        $modo_seguro->save();
        return \redirect()->back()->with('aviso', $msj);
    }
}

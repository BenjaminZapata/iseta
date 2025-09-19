<?php

namespace App\Http\Controllers\Preceptor;

use App\Http\Controllers\Controller;
use App\Models\Habiles;
use App\Services\DiasHabiles;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnValue;

class PreceptorDiasHabiles extends Controller
{

    function __construct()
    {
        $this->middleware('auth:admin');
    }

    function index()
    {
        $noHabiles = Habiles::all()->pluck('fecha')->toArray();

        return view('preceptor.DiasHabiles.index', compact('noHabiles'));
    }

    function store(Request $request)
    {

        if (!$request->has('fecha')) {
            return \redirect()->back()->with('error', 'No has seleccionado ninguna fecha');
        }

        $fecha = explode('-', $request->input('fecha'));

        $dia = $fecha[0];
        $mes = $fecha[1];

        if (strlen($dia) == 1)
            $dia = '0' . $dia;
        if (strlen($mes) == 1)
            $mes = '0' . $mes;

        if (Habiles::where('fecha', "$dia-$mes")->first())
            return \redirect()->back()->with('error', 'ya existe la fecha');

        Habiles::create([
            'fecha' => "$dia-$mes"
        ]);

        return redirect()->back();
    }

    function destroy(Request $request, $habil)
    {
        Habiles::where('fecha', $habil)->delete();
        return redirect()->back()->with('mensaje', 'Se elimino la fecha');
    }
}

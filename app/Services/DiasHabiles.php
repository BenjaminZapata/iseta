<?php

namespace App\Services;

use App\Models\DiaNoHabil;
use App\Models\Habiles;
use DateInterval;
use DatePeriod;
use DateTime;

class DiasHabiles{

    static function desdeHoyHasta($fecha,$fecha2=null){
        
    // fecha de mesa test
    $testStr = $fecha;
    $esNEgativo = false;
    // fecha actual y de mesa
    if($fecha2){
        $currentDateTime = new DateTime($fecha2);
    }else{
        $currentDateTime = new DateTime(); // Obtiene la fecha y hora actual
    }
    $targetDateTime = new DateTime($testStr); // Define la fecha y hora objetivo

    if($currentDateTime>$targetDateTime){
        $esNEgativo=true;
        // return 0;
    }

    $fechaActualHoras = $currentDateTime->format('H');
    $fechaActualMinutos = $currentDateTime->format('i');
    $minutosRestantesHoy = (24*60) - ($fechaActualHoras * 60 + $fechaActualMinutos);
    //si la fecha dada es no habil minutosRestantesHoy = 0

    $fechaDadaHoras = $targetDateTime->format('H');
    $fechaDadaMinutos = $targetDateTime->format('i');
    $minutosHastaMesa = $fechaDadaHoras * 60 + $fechaDadaMinutos;
   
    $minutosTotales = $minutosRestantesHoy + $minutosHastaMesa;
    
    if($esNEgativo){
        $data = $currentDateTime;
        $currentDateTime = $targetDateTime;
        $targetDateTime = $data;
    }

    if($currentDateTime->format('y-m-d') === $targetDateTime->format('y-m-d')){
        $minutosTotales = $minutosTotales - (24*60);
    }

    // intervalo de dias
    $intervalo = new DateInterval('P1D');
    $targetDateTime = new DateTime($targetDateTime->format('y-m-d'));
    $periodo = new DatePeriod($currentDateTime, $intervalo, $targetDateTime);

    $i = 0;
    $dias = [];
    $festivos = DiasHabiles::obtenerFestivos();
    
    foreach ($periodo as $fecha) {
        $i++;

        if($i==1) continue;
    
        if ($fecha->format('D')=='Sun' || $fecha->format('D')=='Sat') { // Excluir sábados y domingos            
            continue;
        }
        
        if (in_array($fecha->format('Y-m-d'), $festivos)) { 
            
            continue; 
        }

        $minutosTotales = $minutosTotales + 24*60;
        
    }
    $final = $minutosTotales / 60;
    
    if($esNEgativo) $final = $final*(-1);

    return $final;

    }
    static function obtenerFestivos() {
        // Aquí puedes agregar o mantener una lista de días festivos para el año dado'
        // Los días festivos pueden variar según la ubicación y el año
        // Este es solo un ejemplo de lista ficticia de días festivos
    
        $festivos = Habiles::all()->pluck('fecha')->toArray();
    
        return $festivos;
    }

    static function esFinDeSemana($fecha){
        // Obtener el dia seleccionado (lunes, martes, miercoles, etc)
        $timestamp = strtotime($fecha);
        $dia = date("l", $timestamp);

        // verificar que no sea sabado ni domingo
        if($dia == 'Saturday' || $dia == 'Sunday'){
            return true;
        }else return false;
    }

    static function esDiaHabil($fecha){

        $diasNoHabiles = DiasHabiles::obtenerFestivos();
        
        $fechaSimple = substr(explode('T', $fecha)[0],5);
        
        $exploded = explode('-',$fechaSimple);
        $fechaSimple = "$exploded[1]-$exploded[0]";
    
        foreach($diasNoHabiles as $dia){
            if($fechaSimple == $dia){
                return \false;
            }
        }

        return true;
    }
}


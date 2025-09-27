<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public $data = [];
    public $defaultFilters = [];
    public $config;

    public function __construct() {
        $this->config = (object) Configuracion::todas();
    }

    public function setFilters($request){

        $defaults = $this->defaultFilters;
        $defaults['filter_field'] = 0;
        $defaults['filter_search_box'] = '';

        foreach($defaults as $key => $value){
            $filters[$key] = $request->has($key)?$request->input($key):$value;
        }
        
        $this->data['filters'] = (object) $filters;
    }
    
}
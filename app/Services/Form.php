<?php

namespace App\Services;

class Form
{

    function select($name, $label, $class, $item = null, $optionsE = [], $options = [])
    {
        if (!isset($options['inputclass'])) {
            $options['inputclass'] = 'p-1';
        }

        return view('Componentes.form.generic-select', [
            'type' => 'text',
            'name' => $name,
            'item' => $item,
            'optionsE' => $optionsE,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }

    function text($name, $label, $class, $item = null, $options = [])
    {

        if (!isset($options['inputclass'])) {
            $options['inputclass'] = 'p-1';
        }

        return view('Componentes.form.text-input', [
            'type' => 'text',
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }
    function textarea($name, $label, $class, $item = null, $options = [])
    {

        if (!isset($options['inputclass'])) {
            $options['inputclass'] = 'p-1';
        }

        return view('Componentes.form.textarea-input', [
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }


    function date($name, $label, $class, $item = null, $options = [])
    {

        if (!isset($options['inputclass'])) {
            $options['inputclass'] = 'p-1';
        }

        return view('Componentes.form.text-input', [
            'type' => 'date',
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }

    function password($name, $label, $class, $item = null, $options = [])
    {

        if (!isset($options['inputclass'])) {
            $options['inputclass'] = 'p-1';
        }

        return view('Componentes.form.text-input', [
            'type' => 'password',
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }


    function checkbox($name, $label, $class, $item = null, $options = [])
    {

        if (!isset($options['inputclass'])) {
            $options['inputclass'] = 'p-1';
        }
        return view('Componentes.form.checkbox-input', [
            'type' => 'checkbox',
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }

    function generate($url, $method, $fieldsets, $mostrar_botones = true)
{
    $fieldsets = (object) $fieldsets;

    if (!isset($options['inputclass'])) {
        $options['inputclass'] = 'p-1';
    }

    return view('Componentes.form.edit-form', [
        'url' => $url,
        'method' => $method,
        'fieldsets' => $fieldsets,
        'mostrar_botones' => $mostrar_botones
    ])->render();
}



    function texthidden($value)
    {
        return view('Componentes.form.text-hidden', [
            'value' => $value,
        ])->render();
    }

    function file($name, $label, $class, $item = null, $options = [])
{
    if (!isset($options['inputclass'])) {
        $options['inputclass'] = 'p-1';
    }

    return view('Componentes.form.file-input', [
        'type' => 'file',
        'name' => $name,
        'item' => $item,
        'class' => $class,
        'label' => $label,
        'options' => $options
    ])->render();
}


}
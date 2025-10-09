<?php

namespace App\Services;

class Form
{
    // SELECT
    public function select($name, $label, $class, $item = null, $optionsE = [], $options = [])
    {
        $options['inputclass'] = $options['inputclass'] ?? 'p-1';

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

    // TEXT INPUT
    public function text($name, $label, $class, $item = null, $options = [])
    {
        $options['inputclass'] = $options['inputclass'] ?? 'p-1';

        return view('Componentes.form.text-input', [
            'type' => 'text',
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }

    // TEXTAREA
    public function textarea($name, $label, $class, $item = null, $options = [])
    {
        $options['inputclass'] = $options['inputclass'] ?? 'p-1';

        return view('Componentes.form.textarea-input', [
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }

    // DATE INPUT
    public function date($name, $label, $class, $item = null, $options = [])
    {
        $options['inputclass'] = $options['inputclass'] ?? 'p-1';

        return view('Componentes.form.text-input', [
            'type' => 'date',
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }

    // PASSWORD INPUT
    public function password($name, $label, $class, $item = null, $options = [])
    {
        $options['inputclass'] = $options['inputclass'] ?? 'p-1';

        return view('Componentes.form.text-input', [
            'type' => 'password',
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }

    // CHECKBOX
    public function checkbox($name, $label, $class, $item = null, $options = [])
    {
        $options['inputclass'] = $options['inputclass'] ?? 'p-1';

        return view('Componentes.form.checkbox-input', [
            'type' => 'checkbox',
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }

    // FILE INPUT
    public function file($name, $label, $class, $item = null, $options = [])
    {
        $options['inputclass'] = $options['inputclass'] ?? 'p-1';

        return view('Componentes.form.file-input', [
            'type' => 'file',
            'name' => $name,
            'item' => $item,
            'class' => $class,
            'label' => $label,
            'options' => $options
        ])->render();
    }

    // FORM GENERATOR
    public function generate($url, $method, $fieldsets)
    {
        $fieldsets = (object) $fieldsets;

        return view('Componentes.form.edit-form', [
            'url' => $url,
            'method' => $method,
            'fieldsets' => $fieldsets
        ])->render();
    }

    // HIDDEN TEXT
    public function texthidden($value)
    {
        return view('Componentes.form.text-hidden', [
            'value' => $value,
        ])->render();
    }
}
 
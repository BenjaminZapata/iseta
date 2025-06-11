<?php

namespace Illuminate\Support\Facades;

interface Auth
{
    /**
     * @return \App\Models\Alumno|false
     */
    public static function loginUsingId(mixed $id, bool $remember = false);

    /**
     * @return \App\Models\Alumno|false
     */
    public static function onceUsingId(mixed $id);

    /**
     * @return \App\Models\Alumno|null
     */
    public static function getUser();

    /**
     * @return \App\Models\Alumno
     */
    public static function authenticate();

    /**
     * @return \App\Models\Alumno|null
     */
    public static function user();

    /**
     * @return \App\Models\Alumno|null
     */
    public static function logoutOtherDevices(string $password);

    /**
     * @return \App\Models\Alumno
     */
    public static function getLastAttempted();
}
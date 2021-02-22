<?php

namespace System;


/*
 * Библиотека общих функций
 */
class Lib
{

    // Форматированный вывод переменной на экран
    public static function var_dump($variable)
    {
        echo "<pre>";
        var_dump($variable);
        echo "</pre>";
    }
    public static function print_r($variable)
    {
        echo "<pre>";
        print_r($variable);
        echo "</pre>";
    }


}
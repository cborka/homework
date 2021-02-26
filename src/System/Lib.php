<?php

namespace System;

use System\Render;

/*
 * Библиотека общих функций
 */
class Lib
{

    /*
     * Форматированный вывод переменной на экран
     */
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
    // Возвращает var_dump() в одну строку
    public static function var_dump1($variable)
    {
        return str_replace("  ", " ", str_replace("\n", "", var_export($variable, true)));
    }

    /*
     * Проверка результатов выполнения запросов к БД
     */
    public static function checkPDOError($str) {
        if ($str === "PDOError") {
            Render::render("Ошибка выполнения запроса к базе данных. <br>Подробности смотрите в логах.");
            die();
        }
    }


}
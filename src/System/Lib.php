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
    // Возвращает как var_dump(), но в одну строку и убирает лишние пробелы
    public static function var_dump1($variable)
    {
        return preg_replace('/ {2,}/',' ',str_replace("\r", " " , str_replace("\n", " ", var_export($variable, true))));
    }

    /*
     * Проверка результатов выполнения запросов к БД
     */
    public static function checkPDOError($str) {
        if ($str === "PDOError") {
//            echo  "Ошибка выполнения запроса к базе данных. <br>Подробности смотрите в логах.";
            Render::render("Ошибка выполнения запроса к базе данных. <br>Подробности смотрите в логах.");
            die();
        }
    }

}
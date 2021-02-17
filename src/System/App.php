<?php

namespace System;

class App
{
    function __construct()
    {
    }

    public static function run()
    {
//        $path = $_SERVER['REQUEST_URI'];
        $pathParts = explode('/', $_SERVER['REQUEST_URI']);
//        $controller = $pathParts[1];
//        $action = $pathParts[2];
        $controller = 'Controllers\\' . $pathParts[1] . 'Controller';
        $action = 'action' . ucfirst($pathParts[2]);

        echo $pathParts[1] . '->' . $pathParts[2] . '<br>';
//        echo $controller . '->' . $action . '<br>';

        if (!class_exists($controller)) {
            throw new \ErrorException("Ошибка: $controller не существует.");
        }

        $objController = new $controller;

        if (!method_exists($objController, $action)) {
            throw new \ErrorException("Ошибка: $action не существует.");
        }

        $objController->$action();
    }
}
<?php

namespace System;

class App
{
    function __construct()
    {
    }

    public static function run()
    {
        $path = $_SERVER['REQUEST_URI'];
        $pathParts = explode('/', $path);
        $controller = $pathParts[1];
        $action = $pathParts[2];
        $controller = 'Controllers\\' . $controller . 'Controller';
        $action = 'action' . ucfirst($action);

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
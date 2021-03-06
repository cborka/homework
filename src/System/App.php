<?php

namespace System;

use System\Logger;

class App
{
    function __construct()
    {
    }

    public static function run()
    {
        global $logger;

        $pathPath = explode('?', $_SERVER['REQUEST_URI']); // Отделяю GET-параметры

        if ($pathPath[0] === '/') {
            $pathPath[0] = '/users/home';
//            header('location: /users/home');
        }

        $pathParts = explode('/', $pathPath[0]);

//        $pathParts = explode('/', $_SERVER['REQUEST_URI']);
        $controller = 'Controllers\\' . $pathParts[1] . 'Controller';
        $action = 'action' . ucfirst($pathParts[2]);

        $logger->debug(self::class . '::run: ' . $pathParts[1] . '->' . $pathParts[2]);

//        echo $pathParts[1] . '->' . $pathParts[2] . '<br>';
//        echo $controller . '->' . $action . '<br>';

        if (!class_exists($controller)) {
            throw new \ErrorException("Ошибка: $pathParts[1] не существует.");
        }

        $objController = new $controller;

        if (!method_exists($objController, $action)) {
            throw new \ErrorException("Ошибка: $pathParts[2] не существует.");
        }

        $params = [];
        $count = count($pathParts);

        // Путь заканчивается на /, последний элемент массива пуст
        if (trim($pathParts[$count-1]) === '') {
            $count--;
        }

        for ($i = 3; $i < $count; $i++) {
            $params[] = $pathParts[$i];
        }

        if ($params === []) {
            $objController->$action();
        } else {
            $objController->$action($params);
        }

    }
}
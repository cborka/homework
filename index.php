<?php

// Включаем режим строгой типизации
declare(strict_types=1);

use System\App;
use System\Render;
use System\Logger;
use System\MyPdo;

global $logger;
global $pdo;

// Подключаем файл реализующий автозагрузку
require 'vendor/autoload.php';

// Создаю глобальный логгер
try {
    $logger = new Logger();
} catch (\Error $e) {
    echo $e->getMessage() . '<br>';
    exit;
}

// Подключаюсь к БД
try {
    $mypdo = new MyPdo('mysql:host=93.189.42.2;dbname=myfs', 'bor', '432');
    $pdo = $mypdo->getDbh();
    $logger->info("Подключена БД");
} catch (PDOException $e) {
    $logger->error( "Ошибка: " . $e->getMessage() . "<br/>");
    die();
}


//echo 'root_dir = ' . __DIR__;
//echo '<pre>'; print_r($_SERVER); echo '</pre>';

// Запускаем приложение
try {
    App::run();
} catch (\ErrorException $e) {
    Render::render($e->getMessage() . '<br>');
}

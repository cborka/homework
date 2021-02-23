<?php

// Включаем режим строгой типизации
declare(strict_types=1);


//echo bin2hex(random_bytes(32));
//phpinfo();
//die();

// Подключаем файл реализующий автозагрузку
require 'vendor/autoload.php';

use System\App;
use System\Render;
use System\Logger;
use System\MyPdo;

global $logger;
global $pdo;


// Создаю глобальный логгер
try {
    $logger = new Logger();
} catch (\Error $e) {
    echo $e->getMessage() . '<br>';
    exit;
}

// Подключаюсь к БД
try {
    $mypdo = new MyPdo('mysql:host=93.189.42.2;dbname=homeworks', 'boris', '14321');
    $pdo = $mypdo->getDbh();
    $logger->info("Подключена БД");
} catch (PDOException $e) {
    $logger->error( "Ошибка: " . $e->getMessage() . "<br/>");
    die();
}

// Запускаем приложение
try {
    App::run();
} catch (\ErrorException $e) {
    $logger->error( "Ошибка: " . $e->getMessage());
//    Render::render($e->getMessage() . '<br>');
}

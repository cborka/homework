<?php

// Включаем режим строгой типизации
declare(strict_types=1);

//echo $_SERVER['REQUEST_URI'] . '<br>';
//var_dump(parse_url($_SERVER['REQUEST_URI']));
//var_dump($_GET);

//echo bin2hex(random_bytes(32));
//phpinfo();
//die();

// Подключаем файл реализующий автозагрузку
require 'vendor/autoload.php';

use System\Logger;
use System\MyPdo;
use System\App;
use System\Render;

global $logger;
global $mypdo;
global $dbh;


// Создаю глобальный логгер
try {
    $logger = new Logger();
} catch (\Error $e) {
    echo "Ошибка: {$e->getMessage()}";
    exit;
}

$logger->info("---------------------------------------------------------------");


// Подключаюсь к БД, глобальная ссылка на подключение
try {
    $mypdo = new MyPdo('mysql:host=93.189.42.2;dbname=homework', 'bor', '432');
    $dbh = $mypdo->getDbh();
    $logger->info("Подключена БД");
} catch (PDOException $e) {
    $logger->error( "MyPdo: Ошибка: {$e->getMessage()} ");
    echo "MyPdo: Ошибка: {$e->getMessage()} ";
    die();
}

// Запускаем приложение
try {
    App::run();
} catch (\ErrorException $e) {
    $logger->warning( "App::run: " . $e->getMessage());
    Render::render( "App::run: " . $e->getMessage());
}

$logger->error( "The END!");
<?php

// Включаем режим строгой типизации
declare(strict_types=1);
session_start();

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
global $dbh;

// Создаю глобальный логгер
try {
    $logger = new Logger();
} catch (\Error $e) {
    echo "Ошибка: {$e->getMessage()}";
    exit;
}

$logger->info("------------------------ BEGIN ----------------------");


// Подключаюсь к БД, глобальная ссылка на подключение
$mypdo = new MyPdo();
$dbh = $mypdo->getDbh();
$logger->info("Подключена БД ");

// Запускаем приложение
try {
    App::run();
} catch (\ErrorException $e) {
    $logger->warning( "App::run: " . $e->getMessage());
    Render::render( "App::run: " . $e->getMessage());
}

$logger->error( "END!");
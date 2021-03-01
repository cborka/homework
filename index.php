<?php

// Включаем режим строгой типизации
declare(strict_types=1);
session_start();

//echo $_SERVER['REQUEST_URI'] . '<br>';
//var_dump(parse_url($_SERVER['REQUEST_URI']));
//var_dump($_GET);

//echo bin2hex(random_bytes(32));
//phpinfo();

// Подключаем файл реализующий автозагрузку
require 'vendor/autoload.php';

use System\Logger;
use System\MyPdo;
use System\App;
use System\Render;

//use System\Lib;
//Lib::var_dump($_SERVER);
//die();

global $logger;
global $dbh;

$var = 'qqq';
echo 'hi' . date("Y-m-d");

//XDEBUG_SESSION_START=1


die();

// Создаю глобальный логгер
try {
    $logger = new Logger();
} catch (\Error $e) {
    echo "Ошибка: {$e->getMessage()}";
    exit;
}

// Подключаюсь к БД, глобальная ссылка на подключение
$mypdo = new MyPdo();
$dbh = $mypdo->getDbh();

$logger->notice("-----BEGIN ----- {$_SERVER['SERVER_ADDR']} ----- {$_SERVER['HTTP_HOST']} --- {$_SERVER['REQUEST_URI']} ----- {$_SESSION['login']} -----");

// Запускаем приложение
try {
    App::run();
} catch (\ErrorException $e) {
    echo "App::run: " . $e->getMessage();
    $logger->warning( "App::run: " . $e->getMessage());
    Render::render( "App::run: " . $e->getMessage());
}

$logger->error( "END!");
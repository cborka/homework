<?php

// Включаем режим строгой типизации
declare(strict_types=1);


//phpinfo();
//die();


//// Валидация дебагера, раскомментирую когда будет надо
//if ($_SERVER['REQUEST_URI'] === '/_intellij_phpdebug_validator.php') {
//    header('HTTP/1.0 200 OK');
//    echo "Wellcome";
//    die();
//}

//echo substr($_SERVER['REQUEST_URI'], 0, 6);
//die();
//echo substr($_SERVER['REQUEST_URI'], -10, 10);
//die();

// Всякие боты, прикидываюсь чайником
$bot = (
    ((strpos($_SERVER['REQUEST_URI'], '.php') > 0) && (substr($_SERVER['REQUEST_URI'], -10, 10) !== '/index.php')) ||
    (substr($_SERVER['REQUEST_URI'], 0, 7) === '/vendor') ||
    (substr($_SERVER['REQUEST_URI'], 0, 6) === '/texts') ||
    (substr($_SERVER['REQUEST_URI'], 0, 5) === '/logs') ||
    (substr($_SERVER['REQUEST_URI'], 0, 4) === '/src') ||
    (substr($_SERVER['REQUEST_URI'], 0, 6) === '/shell') ||
    (substr($_SERVER['REQUEST_URI'], 0, 4) === '/js/') ||
    (substr($_SERVER['REQUEST_URI'], 0, 5) === '/site') ||
    (substr($_SERVER['REQUEST_URI'], 0, 2) === '/?') ||
    (substr($_SERVER['REQUEST_URI'], 0, 2) === '//') ||
    (substr($_SERVER['REQUEST_URI'], 0, 3) === '//?') ||
    (substr($_SERVER['REQUEST_URI'], 0, 7) === '/config')
//    ($_SERVER['REMOTE_ADDR'] !== '192.168.72.1')
);
if ($bot) {
    header('HTTP/1.0 418 I’m a teapot');
//    die(); // а умрешь когда отчитаешься
}

define("DOCUMENT_ROOT",    $_SERVER['DOCUMENT_ROOT'] . '/..');
//echo DOCUMENT_ROOT;

session_start();

//echo $_SERVER['REQUEST_URI'] . '<br>';
//var_dump(parse_url($_SERVER['REQUEST_URI']));
//var_dump($_GET);

//echo bin2hex(random_bytes(32));
//phpinfo();



// Подключаем файл реализующий автозагрузку
require '../vendor/autoload.php';

use System\Logger;
use System\MyPdo;
use System\App;
use System\Render;

//use System\Lib;
//Lib::var_dump($_SERVER);
//die();

global $logger;
global $dbh;

//$var = 'qqq';
//echo 'hi' . date("Y-m-d");
//XDEBUG_SESSION_START=1


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

if($bot) {
//    echo "Bot";
    $logger->notice("Bot {$_SERVER['REMOTE_ADDR']} --- {$_SERVER['HTTP_HOST']} - {$_SERVER['REQUEST_URI']} - {$_SESSION['login']} -");
    die();
}

// Render::render('Рендер строки, проверка переменных.', null, ['left_col' => '', 'menu' => '']);
// die();

$logger->notice("-----BEGIN ----- {$_SERVER['REMOTE_ADDR']} ----- {$_SERVER['HTTP_HOST']} --- {$_SERVER['REQUEST_URI']} ----- {$_SESSION['login']} -----");

// Запускаем приложение
try {
//    echo "app run";
    App::run();
} catch (\ErrorException $e) {
    $logger->warning( "App::run: " . $e->getMessage());
    header('HTTP/1.0 404 Not found');
    die();
}

$logger->error( "END!");
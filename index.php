<?php

// Включаем режим строгой типизации
declare(strict_types=1);

use System\App;
use System\Render;

// Подключаем файл реализующий автозагрузку
require 'vendor/autoload.php';

//echo 'root_dir = ' . __DIR__;
//echo '<pre>'; print_r($_SERVER); echo '</pre>';

// Запускаем приложение
try {
    App::run();
} catch (\ErrorException $e) {
    Render::render($e->getMessage() . '<br>');
}

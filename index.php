<?php

// Включаем режим строгой типизации
declare(strict_types=1);

use System\App;
use System\Render;

// Подключаем файл реализующий автозагрузку
require 'vendor/autoload.php';

// Запускаем приложение
try {
    App::run();
} catch (\ErrorException $e) {
    Render::render($e->getMessage() . '<br>');
}

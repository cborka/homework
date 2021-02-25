<?php

namespace System;

/*
 * Надо переделать, чтобы было попроще
 */
class Render
{
    public static function render(string $content, $filename = null, $params = [])
    {
        global $logger;

        $logger->debug(self::class .'::render()');

        $layout_file = __DIR__ . '/../Views/partials/layout.php';

        if (!file_exists($layout_file)) {
            // echo self::class .'::render(): не найден файл .../' . basename($layout_file) . '<br>';
            $logger->error(self::class .'::render(): не найден файл .../' . basename($layout_file));
        }

        include $layout_file;
    }

    public static function render_file(string $filename, $params = [])
    {
        global $logger;

        $logger->debug(self::class .'::render_file(): ' . $filename);

        // Пока не решил где формировать полный путь к файлу
//        $fullname = __DIR__ . '/../Views/' . $filename;
        $fullname = $filename;

        if (!file_exists($fullname)) {
            $logger->error(self::class .'::render_file(): не найден файл ' . ($filename));
            //echo self::class .'::render_file(): не найден файл ' . ($filename) . '<br>';
        }

        // Читаем содержимое файла в строку
        try {
            $content = file_get_contents ($fullname);
        } catch (\E_WARNING $e) {
            $logger->error($e->getMessage());
            self::render($e->getMessage());
        }

        // Если текст, то заключаем в тег <pre>
        $extension = pathinfo($fullname, PATHINFO_EXTENSION);
        if ($extension == 'txt') {
//            $content = escapeshellcmd($content);
            $content = strip_tags($content);
            $content = '<pre>' . $content . '</pre>';

            $fullname = null;
            $params = [];
        }
        if ($extension == 'md') {
            $Parsedown = new \Parsedown();
//            $content = escapeshellcmd($content);
            $content = $Parsedown->text($content);
            $fullname = null;
            $params = [];
        }

        self::render($content, $fullname, $params);
    }
}
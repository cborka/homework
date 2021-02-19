<?php

namespace System;


class Render
{
    public static function render(string $content, $filename = null, $params = [])
    {
        $layout_file = __DIR__ . '/../Views/partials/layout.php';

        if (!file_exists($layout_file)) {
            echo self::class .'::render(): не найден файл .../' . basename($layout_file) . '<br>';
        }

        include $layout_file;
    }

    public static function render_file(string $filename, $params = [])
    {
        // Пока не решил где формировать полный путь к файлу
//        $fullname = __DIR__ . '/../Views/' . $filename;
        $fullname = $filename;

        if (!file_exists($fullname)) {
            echo self::class .'::render_file(): не найден файл ' . ($filename) . '<br>';
        }

        // Читаем содержимое файла в строку
        try {
            $content = file_get_contents ($fullname);
        } catch (\E_WARNING $e) {
            self::render($e->getMessage());
        }

        // Если текст, то заключаем в тег <pre>
        $extension = pathinfo($fullname, PATHINFO_EXTENSION);
        echo 'Ext = ' . $extension;
        if ($extension == 'txt') {
//            $content = escapeshellcmd($content);
            $content = strip_tags($content);
            $content = '<pre>' . $content . '</pre>';
        }

        self::render($content, $fullname, $params);
    }

}
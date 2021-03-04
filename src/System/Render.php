<?php

namespace System;

use System\Lib;

/*
 * Надо переделать, чтобы было попроще
 */
class Render
{
    /*
     * Рендер строки
     */
    public static function render(string $content, $filename = null, $params = [])
    {
        global $logger;

        $logger->debug(self::class .'::render()');
//        $logger->debug(self::class .'::render(): ' . $content);

        $layout_file = __DIR__ . '/../Views/partials/layout.php';

        if (!file_exists($layout_file)) {
            // echo self::class .'::render(): не найден файл .../' . basename($layout_file) . '<br>';
            $logger->error(self::class .'::render(): не найден файл .../' . basename($layout_file));
        }

        foreach ($params as $key => $value) {
            $$key = $value;
        }

        include $layout_file;
    }

    /*
     * Рендер файла
     */
    public static function render_file(string $filename, $params = [])
    {
        global $logger;

        $logger->debug(self::class .'::render_file(): ' . $filename);

        $content = self::render_file_to_string($filename, $params);

        self::render($content, null, []);
    }

    /*
     * Рендер файла в строку
     */
    public static function render_file_to_string(string $filename, $params = [])
    {
        global $logger;

        $logger->debug(self::class .'::render_file_to_string(): ' . $filename . ', ' . Lib::var_dump1($params));

        if (substr($filename[0], 0, 1) !== '/') {
            $fullname = $_SERVER['DOCUMENT_ROOT'] . '/src/Views/' . $filename;
        } else {
            $fullname = $filename;
        }

        if (!file_exists($fullname)) {
            $logger->error(self::class .'::render_file_to_string(): не найден файл ' . ($fullname));
            return '';
        }

        // Читаем содержимое файла в строку
        try {
            foreach ($params as $key => $value) {
                if (is_numeric(substr($key, 0, 1))) {
                    $key = 'p' . $key;
                }
                $$key = $value;
            }

            ob_start();
//              echo file_get_contents ($fullname); // через эхо не работает пхп
              include $fullname;
            $content = ob_get_clean();
//            $content = file_get_contents ($fullname);
         } catch (\E_WARNING $e) {
            $logger->error($e->getMessage());
            return '';
        }

        $extension = pathinfo($fullname, PATHINFO_EXTENSION);

        // Если текст, то заключаем в тег <pre>
        if ($extension == 'txt') {
//            $content = escapeshellcmd($content);
            $content = strip_tags($content);
            $content = '<pre>' . $content . '</pre>';
        } else

        // Если маркдаун, то переводим в html
        if ($extension == 'md') {
            $Parsedown = new \Parsedown();
//            $content = escapeshellcmd($content);
            $content = $Parsedown->text($content);
        }

        return $content;
    }




}
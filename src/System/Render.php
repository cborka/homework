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

        // Если задано относительное имя файла
        if (isset($filename) && (substr($filename[0], 0, 1) !== '/')) {
            $filename = $_SERVER['DOCUMENT_ROOT'] . '/src/Views/' . $filename;
        }

        $layout_file = __DIR__ . '/../Views/partials/layout.php';

        if (!file_exists($layout_file)) {
            $logger->error(self::class .'::render(): не найден файл .../' . basename($layout_file));
        }

        // Переписываю параметры из ассоциативного массива в переменные
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

        // Задано относительное имя в .../src/Views/ или абсолютное
        if (substr($filename[0], 0, 1) !== '/') {
            $fullname = $_SERVER['DOCUMENT_ROOT'] . '/src/Views/' . $filename;
        } else {
            $fullname = $filename;
        }

        if (!file_exists($fullname)) {
            $logger->error(self::class .'::render_file_to_string(): не найден файл ' . ($fullname));
            return '';
        }

        // Читаем содержимое файла в строку, подставляем переменные
        try {
            // Переписываю параметры из ассоциативного массива в переменные
            foreach ($params as $key => $value) {
                if (is_numeric(substr($key, 0, 1))) {
                    // если индекс - число, то добавляю букву
                    $key = 'p' . $key;
                }
                $$key = $value;
            }

            ob_start();
                include $fullname;
            $content = ob_get_clean();
         } catch (\E_WARNING $e) {
            $logger->error($e->getMessage());
            return '';
        }

        // Определяем тип файла
        $extension = pathinfo($fullname, PATHINFO_EXTENSION);

        switch ($extension) {
            // Если текст, то преобразуем теги и затем заключаем в тег <pre>
            case 'bat':
            case 'txt':
                $content = htmlspecialchars($content);
                $content = '<pre>' . $content . '</pre>';
                break;
            // Если маркдаун, то преобразуем в html
            case 'md':
                $content = htmlspecialchars($content);
                $Parsedown = new \Parsedown();
                $content = $Parsedown->text($content);
                break;
        }

        return $content;
    }

}
<?php

namespace System;


class Render
{
    public static function render(string $content, $params = [])
    {
        $layout_file = __DIR__ . '/../Views/partials/layout.php';

        if (!file_exists($layout_file)) {
            echo 'view can not be found' . $layout_file;
//            throw new \ErrorException('view can not be found');
        }

        include $layout_file;
    }

}
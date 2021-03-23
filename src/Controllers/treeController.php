<?php

namespace Controllers;

use System\Render;
use System\Lib;
use Models\Tree\Tree;

/*
 * Файловое хранилище
 */

class treeController
{
    private $logger;

    public function __construct()
    {
        global $logger;
        $this->logger = $logger;
    }


    /*
     * Показать дерево
     */
    public function actionShow()
    {
        $this->logger->debug(self::class . '->actionShow()');

        Tree::Show();
    }


}
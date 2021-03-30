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

    /*
     * AJAX: Добавить узел (пункт или папку) в указанную папку
     */
    public function actionAppendNode()
    {
        $this->logger->debug(self::class . '->actionAppendNode()');

        echo Tree::AppendNode($_POST['folder'], $_POST['flags'], $_POST['name']);
    }

    /*
     * AJAX: Удалить узел (пункт или папку) из дерева
     */
    public function actionDeleteNode()
    {
        $this->logger->debug(self::class . '->actionDeletedNode()');

        echo Tree::DeleteNode($_POST['node']);
    }

    /*
     * AJAX: Переименовать узел (пункт или папку)
     */
    public function actionRenameNode()
    {
        $this->logger->debug(self::class . '->actionRenameNode()');

        echo Tree::RenameNode($_POST['node'], $_POST['name']);
//        echo Lib::var_dump1($_POST);
    }

    /*
     * AJAX: Вернуть веточку (папку) дерева
     */
    public function actionGetFolder()
    {
        $this->logger->debug(self::class . '->actionGetFolder()');
        $this->logger->debug(self::class . '->actionGetFolder()' . Lib::var_dump1($_POST));

        echo Tree::GetFolder($_POST['folder']);
//        echo Lib::var_dump1($_POST);
    }



}
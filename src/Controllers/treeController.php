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
        $this->logger->debug(self::class . '->actionAppendNode()' . Lib::var_dump1($_POST));

        echo Tree::AppendNode($_POST['folder'], $_POST['flags'], $_POST['name']);
//        echo Lib::var_dump1($_POST);
    }

    /*
     * AJAX: тест транзакции
     */
    public function actionTt()
    {
        global $mypdo;

        $this->logger->debug(self::class . '->actionTt()');

        $sql =  <<< EOL
            BEGIN;
            INSERT INTO test2(i, s) VALUES (?, ?);
            INSERT INTO testx(i, s) VALUES (?, ?);
            COMMIT;
EOL;
//        $sql =  <<< EOL
//            INSERT INTO test(i, s) VALUES (?, ?);
//EOL;

        $recs = $mypdo->sql_update($sql, [15, "15", 15, "15"]);
        echo 'Привет!' + $recs;

//        echo Tree::AppendNode($_POST['folder'], $_POST['flags'], $_POST['name']);
//        echo Lib::var_dump1($_POST);
    }


}
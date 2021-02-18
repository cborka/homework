<?php

namespace Controllers;

use System\Render;


/*
 * Показывает мои шпаргалки и вообще тексты по темам
 */
class mdnController
{
    private $logger;

    public function __construct()
    {
        global $logger;

        $this->logger = $logger;
    }

    public function actionView()
    {
//        $this->logger->setCurrentLevel(33);

        $this->logger->debug('mdn-view');

        echo "actionView<br>";
        Render::render("actionView<br>");
    }


}
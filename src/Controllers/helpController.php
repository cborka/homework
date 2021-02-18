<?php

namespace Controllers;

use System\Render;


/*
 * Показывает мои шпаргалки и вообще тексты по темам
 */
class helpController
{
    private $logger;

    public function __construct()
    {
        global $logger;

        $this->logger = $logger;
    }


    public function actionGitHelp()
    {
//        $this->logger->setCurrentLevel(33);

        $this->logger->emergency('actionGitHelp()');
        $this->logger->alert('actionGitHelp()');
        $this->logger->critical('actionGitHelp()');
        $this->logger->error('actionGitHelp()');
        $this->logger->warning('actionGitHelp()');
        $this->logger->notice('actionGitHelp()');
        $this->logger->info('actionGitHelp()');
        $this->logger->debug('actionGitHelp()');

        Render::render_file($_SERVER['DOCUMENT_ROOT'] . '/texts/help_git.txt');
    }

    public function actionJqueryHelp()
    {
        Render::render_file($_SERVER['DOCUMENT_ROOT'] . '/texts/help_jquery.txt');
    }

    public function actionHttp()
    {
        Render::render('Http help<br>');
    }

    public function actionCreationLog()
    {
        Render::render_file($_SERVER['DOCUMENT_ROOT'] . '/creation_log.txt');
//        readfile($_SERVER['DOCUMENT_ROOT'] . '/creation_log.txt');
    }

    public function actionJqueryLearn()
    {
        Render::render_file($_SERVER['DOCUMENT_ROOT'] . '/public/jquery.html');
    }

}
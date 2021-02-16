<?php

namespace Controllers;

use System\Render;

class helpController
{
    public function actionGitHelp()
    {
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
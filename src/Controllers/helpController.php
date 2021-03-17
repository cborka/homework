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

//        $this->logger->emergency('actionGitHelp()');
//        $this->logger->alert('actionGitHelp()');
//        $this->logger->critical('actionGitHelp()');
//        $this->logger->error('actionGitHelp()');
//        $this->logger->warning('actionGitHelp()');
//        $this->logger->notice('actionGitHelp()');
//        $this->logger->info('actionGitHelp()');
//        $this->logger->debug('actionGitHelp()');

        $this->logger->debug(self::class . '->actionGitHelp()');

        Render::render_file(DOCUMENT_ROOT . '/texts/help_git.txt');
    }

    public function actionJqueryHelp()
    {
        $this->logger->debug(self::class . '->actionJqueryHelp()');

        Render::render_file(DOCUMENT_ROOT . '/texts/help_jquery.txt');
    }

    public function actionHttp()
    {
        $this->logger->debug(self::class . '->actionHttp()');

//        Render::render('Http help11111111111111111111111112222222233333333333333333322222222222222222222222222222222222222222222222222111111111111111111111111111111111111111111111111111111111111111111111111111111111z<br>', null, [
        Render::render('Http help<br>', null, [
            'footer' => 'Подвал',
//            'left_col' => 'Леваяжеэтоколонкаrrrrrrrrrrrrrrrr22222333333333333333333333333344444444444444444444444666666666666666666666666688888888888888888777777777777777712345678901234567890123456789022222222222222222222222222222222222222222222222rrrrrrrrrrrrrrrrrrrrrrrrz',
            'left_col' => 'rrrrz',
            'right_col' => 'Право111111111111z'
        ]) ;
    }

    public function actionCreationLog()
    {
        $this->logger->debug(self::class . '->actionCreationLog()');

        Render::render_file(DOCUMENT_ROOT . '/creation_log.txt');
    }

    public function actionJqueryLearn()
    {
        $this->logger->debug(self::class . '->actionJqueryLearn()');

        $left = DOCUMENT_ROOT . '/texts/todo.txt';
//        Render::render_file(DOCUMENT_ROOT . '/public/jquery.html');
        Render::render('xxx',DOCUMENT_ROOT . '/public/jquery.html', ['left_col' => $left]);
    }

    public function actionMarkdown()
    {
        $this->logger->debug(self::class . '->actionMarkdown()');

        Render::render_file(DOCUMENT_ROOT . '/texts/markdown.md');
    }

    public function actionDocs()
    {
        $this->logger->debug(self::class . '->actionMarkdown()');

        Render::render_file(DOCUMENT_ROOT . '/texts/docs.md');
    }

    public function actionTest()
    {
        $this->logger->debug(self::class . '->actionTest()');

        Render::render(highlight_string('<?php
    // подсветка синтаксиса, функция ighlight_string()
    phpinfo(); 
    $this->logger->debug(self::class . \'->actionTest()\'); 
?>', true));

    }

    public function actionAjaxRenderTest()
    {
        $this->logger->debug(self::class . '->actionAjaxRenderTest()');

//        Render::render('', DOCUMENT_ROOT . '/src/Views/test/ajax_page.php');
//        Render::render_file(DOCUMENT_ROOT . '/src/Views/test/ajax_page.php');
        Render::render_file(DOCUMENT_ROOT . '/src/Views/test/ajax_render.php');
    }


}
<?php

namespace System;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

define("DOCUMENT_ROOT",    $_SERVER['DOCUMENT_ROOT'] . '/..');


/**
 * Класс Logger реализует LoggerInterface PSR-3
 *
 * Выводятся сообщения меньше текущего ($currentLevel) уровня
 *
 * Сделал цифровые уровни для того чтобы указывать что выводить,
 * Уровень <=40 это критичные вещи, которые нужно выводить всегда, остальное по желанию
 *
 * Сделал глобальную переменную $logger, чтобы реализовать модель Singleton
 *
 * Всё свелось к организации вывода сообщений в нужное место, в файл или базу данных,
 * остальные варианты рассматривать не буду.
 *
 * А ещё в каждом сообщении нужно выводить информацию о пользователе, поэтому сейчас
 * сначала займусь регистрацией пользователей и правами доступа.
 *
 */

class Logger extends AbstractLogger
{
    private $levels = [
        LogLevel::EMERGENCY => 10,
        LogLevel::ALERT     => 20,
        LogLevel::CRITICAL  => 30,
        LogLevel::ERROR     => 40,
        LogLevel::WARNING   => 50,
        LogLevel::NOTICE    => 60,
        LogLevel::INFO      => 70,
        LogLevel::DEBUG     => 80
    ];

    private $fp;
    private $logname;

    private $currentLevel = 81;

    public function __construct()
    {
        $this->logname = 'log' . date("Y-m-d") . '.txt';
        $filename = DOCUMENT_ROOT . "/logs/$this->logname";

        if (!$this->fp = fopen($filename, "a")) {
            throw new \Error("Logger: Не могу открыть файл $this->logname , $filename");
        }
    }
    function __destruct()
    {
        fclose($this->fp);
    }

    public function getCurrentLevel()
    {
        return $this->currentLevel;
    }

    public function setCurrentLevel(int $level)
    {
        $this->currentLevel = $level;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed   $level
     * @param string  $message
     * @param mixed[] $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($level, $message, array $context = array())
    {
        if ($this->currentLevel < $this->levels[$level]) {
            return;
        }

        //  Вывод на экран
   //   echo date("Y-m-d H:i:s\: ") . $this->levels[$level] . '(' . $level . '): ' . $message . '<br>';

        // Вывод в файл
        $this->addMessageToFile(date("Y-m-d H:i:s\: ") . $this->levels[$level] . '(' . $level . '): ' . $message . "\n");

        // Вывод в базу данных
        if ($_SERVER['SERVER_ADDR'] === '93.189.42.2') {
//            echo 'db-> ' . $message . '<br>';
            $this->addMessageToLogs($this->levels[$level], $message);
        }
    }

    /*
     * Запись строки в файл
     */
    private function addMessageToFile($message)
    {
//        $filename =DOCUMENT_ROOT . '/logs/log2.txt';
//
//        if (!$fp = fopen($filename, "a")) {
//            echo "Не могу открыть логфайл";
//            exit;
//        }
        if (fwrite($this->fp, $message) === FALSE) {
            echo "Logger: Не могу произвести запись в файл $this->logname";
            exit;
        }

//        fclose($fp);
    }

    /*
     * Запись строки и сопутствующих данных в таблицу базы данных logs
     */
    private function addMessageToLogs($level, $message)
    {
        global $mypdo;
        global $requestId;

        if (!isset($mypdo)) {
            return;
        }

        $login = isset($_SESSION['login']) ? $_SESSION['login'] :  '';

        $sql = "INSERT INTO logs (request_id, level, login, ip, host, uri, message) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $values = [$requestId, $level, $login, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'], $message];

        $result = $mypdo->sql_insert_into_logs($sql, $values);
        Lib::checkPDOError($result);
    }
    
}
<?php

namespace System;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;


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

    private $currentLevel = 0;

    public function __construct()
    {
//        echo 'I am Logger<br>';
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

        echo date("Y-m-d H:i:s\: ") . $this->levels[$level] . '(' . $level . '): ' . $message . '<br>';
        //Render::render($level . $message . '<br>');
    }


}
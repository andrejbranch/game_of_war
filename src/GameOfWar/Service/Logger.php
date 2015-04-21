<?php

namespace GameOfWar\Service;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monologger;

class Logger extends Monologger
{
    /**
     * file location to log to
     */
    private $logDir;

    public function __construct($logDir)
    {
        $this->logDir = $logDir;
        $this->initialize();
    }

    public function initialize()
    {
        if (!file_exists($this->logDir)) {
            if (!mkdir($this->logDir, 0774, true)) {
                throw new \RunTimeExeption(sprintf('Failed to create log dir %s', $this->logDir));
            }
        }

        parent::__construct('GameOfWar');

        $this->pushHandler(new StreamHandler($this->logDir.'/game_of_war.log', Logger::INFO));
    }
}

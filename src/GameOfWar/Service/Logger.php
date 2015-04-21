<?php

namespace GameOfWar\Service;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monologger;

/**
 * The game of war logger, extended from Monologger
 *
 * @version 1.01
 * @author Andre Jon Branchizio <andrejbranch@gmail.com>
 */
class Logger extends Monologger
{
    /**
     * @var string file path location to log to
     */
    private $logDir;

    /**
     * Initializes a new game of war logger instance
     *
     * @param string $logDir
     */
    public function __construct($logDir)
    {
        $this->logDir = $logDir;
        $this->initialize();
    }

    /**
     * Determines if the log file path exists or not, if not it
     * creates the folder and log file and then initializes the MonoLogger
     */
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

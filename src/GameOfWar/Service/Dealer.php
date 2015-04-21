<?php

namespace GameOfWar\Service;

use GameOfWar\Entity\Player;

class Dealer
{
    /**
     * @var GameOfWar\Service\Logger
     */
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Description
     * @param type Player $player1
     * @param type Player $player2
     * @return type
     */
    public function deal(Player $player1, Player $player2)
    {
        $this->logger->info('Dealing cards to player 1 and 2');
    }
}

<?php

namespace GameOfWar\Service;

use GameOfWar\Entity\Player;

class GameManager
{
    /**
     * @var GameOfWar\Service\Dealer
     */
    private $dealer;

    public function __construct(Dealer $dealer)
    {
        $this->dealer = $dealer;
    }

    public function start()
    {
        $player1 = new Player();
        $player2 = new Player();

        $this->dealer->deal($player1, $player2);
    }
}

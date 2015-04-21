<?php

namespace GameOfWar\Service;

use Doctrine\ORM\EntityManager;
use GameOfWar\Entity\Player;
use GameOfWar\Entity\PlayerCard;
use GameOfWar\Service\Logger;
use Symfony\Component\DependencyInjection\Container;

/**
 * The dealer is a service in charge of shuffling the deck of cards
 * and evenly distributing the cards among the two players
 *
 * @version 1.01
 * @author Andre Jon Branchizio <andrejbranch@gmail.com>
 */
class Dealer
{
    /**
     * @var Doctrine\ORM\EntityManager needed for persisting and flushing objects to the db
     */
    private $em;

    /**
     * @var GameOfWar\Service\Logger
     */
    private $logger;

    /**
     * Initializes a new dealer instance
     *
     * @param EntityManager $em
     * @param Logger $logger
     */
    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * Shuffle and deal cards to players, randomly shuffles and deals
     * 1 card at a time to each player
     *
     * @param array $cards the deck of cards to shuffle and distribute
     * @param GameOfWar\Entity\Player $player1
     * @param GameOfWar\Entity\Player $player2
     */
    public function deal(array $cards, Player $player1, Player $player2)
    {
        $this->logger->info('Shuffling cards');

        shuffle($cards);

        $this->logger->info('Dealing cards to player 1 and 2');

        $i = 1;
        foreach ($cards as $card) {
            $player = $i % 2 == 0 ? $player2 : $player1;
            $playerCard = new PlayerCard($player, $card);
            $player->addPlayerCard($playerCard);
            $this->em->persist($playerCard);
            $i++;
        }

        // insert player cards to the db
        $this->em->flush();

        $this->logger->info('Cards dealt');
    }
}

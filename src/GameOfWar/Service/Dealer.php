<?php

namespace GameOfWar\Service;

use GameOfWar\Entity\Player;
use GameOfWar\Entity\PlayerCard;
use Symfony\Component\DependencyInjection\Container;

class Dealer
{
    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var GameOfWar\Service\Logger
     */
    private $logger;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->em = $this->getEntityManager();
        $this->logger = $this->getLogger();
    }

    /**
     * Shuffle and deal cards to players
     * @param type array $cards
     * @param type Player $player1
     * @param type Player $player2
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

    /**
     * Get doctrine entity manager
     * @return Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->container->get('entity_manager');
    }

    /**
     * Get game of war logger
     * @return GameOfWar\Service\Logger
     */
    private function getLogger()
    {
        return $this->container->get('logger');
    }
}

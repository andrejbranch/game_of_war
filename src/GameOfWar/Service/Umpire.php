<?php

namespace GameOfWar\Service;

use GameOfWar\Entity\Player;
use Symfony\Component\DependencyInjection\Container;

class Umpire
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
        $this->logger = $this->getLogger();
        $this->em = $this->getEntityManager();
    }

    public function handlePlay(Player $player1, Player $player2)
    {
        $player1Card = $player1->getPlayerCardInPlay()->getCard();
        $player2Card = $player2->getPlayerCardInPlay()->getCard();

        $this->logger->info(sprintf('Umpire: %s turns a %s', $player1->getName(), $player1Card));
        $this->logger->info(sprintf('Umpire: %s turns a %s', $player2->getName(), $player2Card));

        $player1CardPower = $player1Card->getPower();
        $player2CardPower = $player2Card->getPower();

        // handle war
        if ($player1CardPower == $player2CardPower) {

            $this->handleWar($player1, $player2);

        // player 1 wins
        } elseif ($player1CardPower > $player2CardPower) {

            $this->handleWin($player1, $player2);

        // player 2 wins
        } else {

            $this->handleWin($player2, $player1);

        }
    }

    private function handleWin(Player $winningPlayer, Player $losingPlayer)
    {
        $this->logger->info(sprintf('%s wins this hand', $winningPlayer->getName()));

        $winningPlayerCard = $winningPlayer->getPlayerCardInPlay();
        $losingPlayerCard = $losingPlayer->getPlayerCardInPlay();

        // add the winning players card to bottom of his deck
        $winningPlayer->addPlayerCard($winningPlayerCard);

        // put winning players table cards at the bottom of the players deck
        foreach ($winningPlayer->getTableCards() as $tableCard) {
            $winningPlayer->addPlayerCard($tableCard);
        }

        // put losing players cards at the bottom of winning players deck
        // confirm that losing player has a card left
        if ($losingPlayerCard) {
            $winningPlayer->addPlayerCard($losingPlayerCard);
        }

        foreach ($losingPlayer->getTableCards() as $tableCard) {
            $winningPlayer->addPlayerCard($tableCard);
        }

        // remove table cards
        $losingPlayer->removeTableCards();
        $winningPlayer->removeTableCards();

        $winningPlayer->removeCardInPlay();
        $losingPlayer->removeCardInPlay();

        // update the db
        $this->em->flush();
    }

    private function handleWar(Player $player1, Player $player2)
    {
        $this->logger->info('Umpire: This means war!');

        // player1 picks 1 of 3 top cards
        if (!$player1->pickWarPlayerCard()) {

            $this->handleWin($player2, $player1);

            return;
        }

        // player2 picks 1 of 3 top cards
        if (!$player2->pickWarPlayerCard()) {

            $this->handleWin($player1, $player2);

            return;
        }

        $this->handlePlay($player1, $player2);
    }

    /**
     * Get game of war logger
     * @return GameOfWar\Service\Logger
     */
    private function getLogger()
    {
        return $this->container->get('logger');
    }

    /**
     * Get doctrine entity manager
     * @return Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->container->get('entity_manager');
    }
}

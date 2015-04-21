<?php

namespace GameOfWar\Service;

use GameOfWar\Entity\Player;
use GameOfWar\Service\Logger;
use Doctrine\ORM\EntityManager;

/**
 * The Umpire service acts as a referee and arbitrator
 * for the game of war. It decides who wins and distributes
 * cards to the winner of each hand.
 *
 * @version 1.01
 * @author Andre Jon Branchizio <andrejbranch@gmail.com>
 */
class Umpire
{
    /**
     * @var Doctrine\ORM\EntityManager for persisting and flushing objects to the db
     */
    private $em;

    /**
     * @var GameOfWar\Service\Logger
     */
    private $logger;

    /**
     * Initializes a new Umpire instance
     *
     * @param Doctrine\ORM\EntityManager $em
     * @param GameOfWar\Service\Logger $logger
     */
    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * Handles a given a play of two played cards, one from each player.
     * If a winner emerges, the win is handled and cards are distributed
     * to the winning player. If a tie emerges, then war is initiated.
     *
     * @param GameOfWar\Entity\Player $player1
     * @param GameOfWar\Entity\Player $player2
     */
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

    /**
     * Handles the completion of a given play by distributing the face
     * down cards to the winning player.
     *
     * @param GameOfWar\Entity\Player $winningPlayer
     * @param GameOfWar\Entity\Player $losingPlayer
     */
    private function handleWin(Player $winningPlayer, Player $losingPlayer)
    {
        $this->logger->info(sprintf('Umpire: %s wins this hand', $winningPlayer->getName()));

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

    /**
     * Handles the initiation of war due to a tie in relative power of cards.
     *
     * @param GameOfWar\Entity\Player $player1
     * @param GameOfWar\Entity\Player $player2
     */
    private function handleWar(Player $player1, Player $player2)
    {
        $this->logger->info('Umpire: This means war!');

        // player1 picks 1 of 3 top cards
        // if player is out of cards the player2 automatically wins
        if (!$player1->pickWarPlayerCard()) {

            $this->handleWin($player2, $player1);

            return;
        }

        // player2 picks 1 of 3 top cards
        // if player is out of cards the player1 automatically wins
        if (!$player2->pickWarPlayerCard()) {

            $this->handleWin($player1, $player2);

            return;
        }

        // both players have picked their cards so now let the Umpire handle the next play
        $this->handlePlay($player1, $player2);
    }
}

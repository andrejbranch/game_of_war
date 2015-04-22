<?php

namespace GameOfWar\Service;

use Doctrine\ORM\EntityManager;
use GameOfWar\Entity\Card;
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
     * @var array card configurations
     */
    private $cardConfigs;

    /**
     * Initializes a new dealer instance
     *
     * @param EntityManager $em
     * @param Logger $logger
     * @param array $cardConfigs
     */
    public function __construct(EntityManager $em, Logger $logger, $cardConfigs)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->cardConfigs = $cardConfigs;
    }

    /**
     * Shuffle and deal cards to players, randomly shuffles and deals
     * 1 card at a time to each player
     *
     * @param GameOfWar\Entity\Player $player1
     * @param GameOfWar\Entity\Player $player2
     */
    public function deal(Player $player1, Player $player2)
    {
        $cards = $this->getCards();

        $this->logger->info('Dealer: Shuffling cards');

        shuffle($cards);

        $this->logger->info('Dealer: Dealing cards to player 1 and 2');

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

        $this->logger->info('Dealer: Cards dealt');
    }

    /**
     * Get the deck of cards from the database
     *
     * @return array of GameOfWar\Entity\Card objects
     */
    public function getCards()
    {
        $cards = $this->getCardRepository()->findAll();

        // if no cards are in the db we should generate them from our config file
        if (empty($cards)) {
            $cards = $this->generateDeck();
        }

        return $cards;
    }

    /**
     * Generate the deck of cards using configurations. This is only called
     * if this is the first time the game is being run.
     *
     * @see config/config.yml
     * @return array of GameOfWar\Entity\Card objects
     */
    private function generateDeck()
    {
        $this->logger->info('Dealer: Generating deck of cards');

        foreach ($this->cardConfigs as $cardConfig) {
            foreach (Card::$validSuits as $suit) {
                $card = new Card($cardConfig['name'], $suit, $cardConfig['power']);
                $this->em->persist($card);
            }
        }

        // insert cards to the db
        $this->em->flush();

        $this->logger->info('Dealer: Deck generated');

        return $this->getCards();
    }

    /**
     * Get card entity repository
     *
     * @return GameOfWar\Entity\CardRepository
     */
    private function getCardRepository()
    {
        return $this->em->getRepository('GameOfWar\Entity\Card');
    }
}

<?php

namespace GameOfWar\Service;

use GameOfWar\Entity\Card;
use GameOfWar\Entity\Player;
use Symfony\Component\DependencyInjection\Container;

class GameManager
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
     * @var GameOfWar\Service\Dealer
     */
    private $dealer;

    /**
     * @var GameOfWar\Service\Logger
     */
    private $logger;

    public function __construct(Container $container, Dealer $dealer)
    {
        $this->container = $container;
        $this->em = $this->getEntityManager();
        $this->dealer = $dealer;
        $this->logger = $this->getLogger();
    }

    public function start($player1Name, $player2Name)
    {
        $cards = $this->getCardRepository()->findAll();

        // if no cards are in the db we should generate them from our config file
        if (empty($cards)) {
            $cards = $this->generateDeck();
        }

        $player1 = new Player($player1Name);
        $player2 = new Player($player2Name);

        $this->em->persist($player1);
        $this->em->persist($player2);

        $this->logger->info(sprintf(
            'Creating player %s and player %s', $player1Name, $player2Name
        ));

        // insert the players to the db
        $this->em->flush();

        // deal the cards to the players
        $this->dealer->deal($cards, $player1, $player2);

        $gameContinues = true;

        while ($gameContinues) {

            $player1Card = $player1->getPlayerCards()->first()->getCard();
            $player2Card = $player2->getPlayerCards()->first()->getCard();

            $this->logger->info(sprintf('%s turns a %s', $player1Name, $player1Card));
            $this->logger->info(sprintf('%s turns a %s', $player2Name, $player2Card));

            $winningPlayer = $player1Card->getPower() > $player2Card->getPower() ? $player1: $player2;
            $losingPlayer = $player1Card->getPower() > $player2Card->getPower() ? $player2: $player1;

            // // handle war
            // if ($player1Card->getPower() == $player2Card->getPower()) {

            //     $this->logger->info('This means war!');

            // } else {
                $this->logger->info(sprintf('%s wins this hand', $winningPlayer->getName()));
                $this->handleWin($winningPlayer, $losingPlayer);
            // }

            if ($player1->getPlayerCards()->count() == 0 | $player2->getPlayerCards()->count() == 0) {
                $gameContinues = false;
            }

            // $player1Cards = $player1->getPlayerCards();
            // $player2Cards = $player2->getPlayerCards();

            // die;
        }
    }

    private function handleWin(Player $winningPlayer, Player $losingPlayer)
    {
        $winningPlayerCard = $winningPlayer->getPlayerCards()->first();
        $losingPlayerCard = $losingPlayer->getPlayerCards()->first();

        // remove losing players card from his deck
        $losingPlayer->removePlayerCard($losingPlayerCard);

        // remove winning players card from his deck and put at the bottom
        $winningPlayer->removePlayerCard($winningPlayerCard);
        $winningPlayer->addPlayerCard($winningPlayerCard);

        // put losing players card at the bottom of winning players deck
        $winningPlayer->addPlayerCard($losingPlayerCard);

        // update the db
        $this->em->flush();
    }

    // private function handlePlay($player1, $player2, $player1)
    // {
    // }

    /**
     * Generate the deck of cards using configurations
     * stored in config/config.yml
     * @return array of cards
     */
    private function generateDeck()
    {
        $this->getLogger()->info('Generating deck of cards');

        $cardConfigs = $this->getCardConfigs();

        foreach ($cardConfigs as $cardConfig) {
            foreach (Card::$validSuits as $suit) {
                $card = new Card($cardConfig['name'], $suit, $cardConfig['power']);
                $this->em->persist($card);
            }
        }

        // insert cards to the db
        $this->em->flush();

        $this->getLogger()->info('Deck generated');

        return $this->getCardRepository()->findAll();
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

    /**
     * Get card entity repository
     * @return GameOfWar\Entity\CardRepository;
     */
    private function getCardRepository()
    {
        return $this->getEntityManager()->getRepository('GameOfWar\Entity\Card');
    }

    /**
     * Get the card configurations
     * @return array
     */
    private function getCardConfigs()
    {
        return $this->container->getParameter('cards');
    }
}

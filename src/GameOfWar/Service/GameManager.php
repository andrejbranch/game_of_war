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
     * @var GameOfWar\Service\Umpire
     */
    private $umpire;

    /**
     * @var GameOfWar\Service\Logger
     */
    private $logger;

    /**
     * @var array cards on the table
     */
    private $playedCards = array();

    public function __construct(Container $container, Dealer $dealer)
    {
        $this->container = $container;
        $this->em = $this->getEntityManager();
        $this->dealer = $dealer;
        $this->umpire = $this->getUmpire();
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

            $this->umpire->handlePlay($player1, $player2);

            $player1CardCount = $player1->getCardCount();
            $player2CardCount = $player2->getCardCount();

            $this->logger->info(sprintf('GameManager: %s now has %s cards', $player1->getName(), $player1CardCount));
            $this->logger->info(sprintf('GameManager: %s now has %s cards', $player2->getName(), $player2CardCount));

            if ($player1->getCardCount() == 0 | $player2->getCardCount() == 0) {
                $gameContinues = false;
            }
        }

        $winningPlayer = $player1CardCount == 0 ? $player2 : $player1;

        $this->logger->info(sprintf('GameManager: %s wins!', $winningPlayer->getName()));

        return $winningPlayer;
    }

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

    /**
     * Get the game of war umpire
     * @return GameOfWar\Service\Umpire
     */
    private function getUmpire()
    {
        return $this->container->get('umpire');
    }
}

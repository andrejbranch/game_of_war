<?php

namespace GameOfWar\Service;

use GameOfWar\Entity\Card;
use GameOfWar\Entity\Game;
use GameOfWar\Entity\Player;
use Symfony\Component\DependencyInjection\Container;

/**
 * The GameManager is a service in charge of handling the flow of
 * the game of war.
 *
 * @version 1.01
 * @author Andre Jon Branchizio <andrejbranch@gmail.com>
 */
class GameManager
{
    /**
     * @var Symfony\Component\DependencyInjection\Container The dependency injection container
     */
    private $container;

    /**
     * @var Doctrine\ORM\EntityManager for flushing and persisting objects to db
     */
    private $em;

    /**
     * @var GameOfWar\Service\Dealer deals the deck of cards
     */
    private $dealer;

    /**
     * @var GameOfWar\Service\Umpire The referee and arbitrator
     */
    private $umpire;

    /**
     * @var GameOfWar\Service\Logger
     */
    private $logger;

    /**
     * Initializes a new GameManager instance
     *
     * @param Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->dealer = $this->getDealer();
        $this->em = $this->getEntityManager();
        $this->umpire = $this->getUmpire();
        $this->logger = $this->getLogger();
    }

    /**
     * Starts the game of war
     *
     * @param string $player1Name player name entered from command line
     * @param string $player2Name player name entered from command line
     *
     * @return GameOfWar\Entity\Player the winning player
     */
    public function start($player1Name, $player2Name)
    {
        $player1 = new Player($player1Name);
        $player2 = new Player($player2Name);

        $this->em->persist($player1);
        $this->em->persist($player2);

        $this->logger->info(sprintf(
            'GameManager: Creating player %s and player %s', $player1Name, $player2Name
        ));

        // insert the players to the db
        $this->em->flush();

        $game = new Game($player1, $player2);

        $this->em->persist($game);

        $this->logger->info('GameManager: creating new game');

        $this->em->flush();

        // deal the cards to the players
        $this->dealer->deal($player1, $player2);

        $this->logger->info('GameManager: game starting');

        $gameContinues = true;

        while ($gameContinues) {

            $this->umpire->handlePlay($game, $player1, $player2);

            $player1CardCount = $player1->getCardCount();
            $player2CardCount = $player2->getCardCount();

            $this->logger->info(sprintf('GameManager: %s now has %s cards', $player1->getName(), $player1CardCount));
            $this->logger->info(sprintf('GameManager: %s now has %s cards', $player2->getName(), $player2CardCount));

            if ($player1CardCount == 0 || $player2CardCount == 0) {
                $gameContinues = false;
            }
        }

        $winningPlayer = $player1CardCount == 0 ? $player2 : $player1;

        $this->logger->info(sprintf('GameManager: %s wins!', $winningPlayer->getName()));

        $game->setWinningPlayer($winningPlayer);

        $this->em->flush();

        return $winningPlayer;
    }

    /**
     * Get the game of war card dealer
     *
     * @return GameOfWar\Service\Dealer
     */
    private function getDealer()
    {
        return $this->container->get('dealer');
    }

    /**
     * Get doctrine entity manager
     *
     * @return Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->container->get('entity_manager');
    }

    /**
     * Get game of war logger
     *
     * @return GameOfWar\Service\Logger
     */
    private function getLogger()
    {
        return $this->container->get('logger');
    }

    /**
     * Get the game of war umpire
     *
     * @return GameOfWar\Service\Umpire
     */
    private function getUmpire()
    {
        return $this->container->get('umpire');
    }
}

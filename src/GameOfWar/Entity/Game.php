<?php

namespace GameOfWar\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="games")
 *
 * The entity model for a game of war game
 *
 * @version 1.01
 * @author Andre Jon Branchizio <andrejbranch@gmail.com>
 */
class Game
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int the game id
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="GameOfWar\Entity\Player")
     * @ORM\JoinColumn(name="player1_id", nullable=false)
     * @var GameOfWar\Entity\Player
     */
    protected $player1;

    /**
     * @ORM\OneToOne(targetEntity="GameOfWar\Entity\Player")
     * @ORM\JoinColumn(name="player2_id", nullable=false)
     * @var GameOfWar\Entity\Player
     */
    protected $player2;

    /**
     * @ORM\OneToOne(targetEntity="GameOfWar\Entity\Player")
     * @ORM\JoinColumn(name="winning_player_id", nullable=true)
     * @var GameOfWar\Entity\Player
     */
    protected $winningPlayer;

    /**
     * @ORM\OneToMany(targetEntity="GameOfWar\Entity\Round", mappedBy="game")
     * @var Doctrine\Common\Collections\ArrayCollection the rounds of the game
     */
    protected $rounds;

    /**
     * Initializes a new Game
     *
     * @param GameOfWar\Entity\Player $player1
     * @param GameOfWar\Entity\Player $player2
     */
    public function __construct(Player $player1, Player $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->rounds = new ArrayCollection();
    }

    /**
     * Set the id of the game
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the id of the game
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set player 1
     *
     * @param GameOfWar\Entity\Player $player1
     */
    public function setPlayer1(Player $player1)
    {
        $this->player1 = $player1;
    }

    /**
     * Get the player 1 object
     *
     * @return GameOfWar\Entity\Player
     */
    public function getPlayer1()
    {
        return $this->player1;
    }

    /**
     * Set player 2
     *
     * @param GameOfWar\Entity\Player $player2
     */
    public function setPlayer2(Player $player2)
    {
        $this->player2 = $player2;
    }

    /**
     * Get the player 2 object
     *
     * @return GameOfWar\Entity\Player
     */
    public function getPlayer2()
    {
        return $this->player2;
    }

    /**
     * Set the winning player
     *
     * @param GameOfWar\Entity\Player $winningPlayer
     */
    public function setWinningPlayer(Player $winningPlayer)
    {
        $this->winningPlayer = $winningPlayer;
    }

    /**
     * Get the winning player
     *
     * @return GameOfWar\Entity\Player
     */
    public function getWinningPlayer()
    {
        return $this->winningPlayer;
    }

    /**
     * Add a round to the game
     *
     * @param GameOfWar\Entity\Round $round
     */
    public function addRound(Round $round)
    {
        $this->rounds->add($round);
    }

    /**
     * Get the rounds in the game
     *
     * @return Doctrine\Common\Collections\ArrayCollection collection of rounds
     */
    public function getRounds()
    {
        return $this->rounds;
    }
}

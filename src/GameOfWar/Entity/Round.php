<?php

namespace GameOfWar\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="rounds")
 *
 * The entity model for a round in the game of war
 *
 * @version 1.01
 * @author Andre Jon Branchizio <andrejbranch@gmail.com>
 */
class Round
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int the round id
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     * @var bool whether the round resulted in war or not
     */
    protected $isWar = false;

    /**
     * @ORM\ManyToOne(targetEntity="GameOfWar\Entity\Card")
     * @ORM\JoinColumn(name="player1_card_played_id", nullable=true)
     * @var GameOfWar\Entity\Card the card object that player 1 played for the round
     */
    protected $player1CardPlayed;

    /**
     * @ORM\ManyToOne(targetEntity="GameOfWar\Entity\Card")
     * @ORM\JoinColumn(name="player2_card_played_id", nullable=true)
     * @var GameOfWar\Entity\Card the card object that player 2 played for the round
     */
    protected $player2CardPlayed;

    /**
     * @ORM\ManyToOne(targetEntity="GameOfWar\Entity\Player")
     * @ORM\JoinColumn(name="winning_player_id", nullable=true)
     * @var GameOfWar\Entity\Player
     */
    protected $winningPlayer;

    /**
     * @ORM\ManyToOne(targetEntity="GameOfWar\Entity\Game", inversedBy="rounds")
     * @ORM\JoinColumn(name="game_id", nullable=false)
     * @var GameOfWar\Entity\Game the game the round is in
     */
    protected $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * Set the winning player of the round
     *
     * @param GameOfWar\Entity\Player $winningPlayer
     */
    public function setWinningPlayer(Player $winningPlayer)
    {
        $this->winningPlayer = $winningPlayer;
    }

    /**
     * Get the winning player of the round
     *
     * @return GameOfWar\Entity\Player
     */
    public function getWinningPlayer()
    {
        return $this->winningPlayer;
    }


    /**
     * Set if this round resulted in war or not
     *
     * @param bool $isWar
     */
    public function setIsWar($isWar)
    {
        $this->isWar = (bool) $isWar;
    }

    /**
     * Get whether the round resulted in war or not
     *
     * @return bool
     */
    public function getIsWar()
    {
        return $this->isWar;
    }

    /**
     * Set the card player 1 played for the round
     *
     * @param GameOfWar\Entity\Card $card
     */
    public function setPlayer1CardPlayed(Card $player1CardPlayed)
    {
        $this->player1CardPlayed = $player1CardPlayed;
    }

    /**
     * Get the card player 1 played for the round
     *
     * @return GameOfWar\Entity\Card
     */
    public function getPlayer1CardPlayer()
    {
        return $this->player1CardPlayed;
    }

    /**
     * Set the card player 2 played for the round
     *
     * @param GameOfWar\Entity\Card $card
     */
    public function setPlayer2CardPlayed(Card $player2CardPlayed)
    {
        $this->player2CardPlayed = $player2CardPlayed;
    }

    /**
     * Get the card player 2 played for the round
     *
     * @return GameOfWar\Entity\Card
     */
    public function getPlayer2CardPlayer()
    {
        return $this->player2CardPlayed;
    }
}

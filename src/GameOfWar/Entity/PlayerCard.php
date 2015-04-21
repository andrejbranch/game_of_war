<?php

namespace GameOfWar\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="player_cards")
 *
 * The entity model for a player card, this is a linker from player to card
 *
 * @version 1.01
 * @author Andre Jon Branchizio <andrejbranch@gmail.com>
 */
class PlayerCard
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="GameOfWar\Entity\Player", inversedBy="cards")
     * @ORM\JoinColumn(name="player_id", nullable=false)
     * @var GameOfWar\Entity\Player the player object that owns the linked card
     */
    protected $player;

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="GameOfWar\Entity\Card")
     * @ORM\JoinColumn(name="card_id", nullable=false)
     * @var GameOfWar\Entity\Card the card object
     */
    protected $card;

    /**
     * Initializes a new PlayerCard
     *
     * @param Player $player
     * @param Card $card
     */
    public function __construct(Player $player, Card $card)
    {
        $this->player = $player;
        $this->card = $card;
    }

    /**
     * Set the player who owns the card
     *
     * @param Player $player
     */
    public function setPlayer(Player $player)
    {
        $this->player = $player;
    }

    /**
     * Get the player who owns the card
     *
     * @return GameOfWar\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set the card the player owns
     *
     * @param Card $card
     */
    public function setCard(Card $card)
    {
        $this->card = $card;
    }

    /**
     * Get the card the player owns
     *
     * @return GameOfWar\Entity\Card
     */
    public function getCard()
    {
        return $this->card;
    }
}

<?php

namespace GameOfWar\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="player_cards")
 */
class PlayerCard
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="GameOfWar\Entity\Player", inversedBy="cards")
     * @ORM\JoinColumn(name="player_id", nullable=false)
     */
    protected $player;

    /**
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="GameOfWar\Entity\Card")
     * @ORM\JoinColumn(name="card_id", nullable=false)
     */
    protected $card;

    public function __construct(Player $player, Card $card)
    {
        $this->player = $player;
        $this->card = $card;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPlayer(Player $player)
    {
        $this->player = $player;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function setCard(Card $card)
    {
        $this->card = $card;
    }

    public function getCard()
    {
        return $this->card;
    }
}

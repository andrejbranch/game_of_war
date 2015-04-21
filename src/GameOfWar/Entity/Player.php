<?php

namespace GameOfWar\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="players")
 */
class Player
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /** @ORM\OneToMany(targetEntity="GameOfWar\Entity\PlayerCard", mappedBy="player") */
    protected $playerCards;

    public function __construct($name)
    {
        $this->name = $name;
        $this->playerCards = new ArrayCollection();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function addPlayerCard(PlayerCard $playerCard)
    {
        $this->playerCards->add($playerCard);
    }

    public function removePlayerCard(PlayerCard $playerCard)
    {
        $this->playerCards->removeElement($playerCard);
    }

    public function getPlayerCards()
    {
        return $this->playerCards;
    }
}

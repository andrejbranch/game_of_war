<?php

namespace GameOfWar\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="CardRepository")
 * @ORM\Table(name="cards")
 */
class Card
{
    const SUIT_CLUB = "Club";
    const SUIT_DIAMOND = "Diamond";
    const SUIT_HEART = "Heart";
    const SUIT_SPADE = "Space";

    /**
     * @var array valid suit values
     */
    public static $validSuits = array(
        self::SUIT_CLUB,
        self::SUIT_DIAMOND,
        self::SUIT_HEART,
        self::SUIT_SPADE,
    );

    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $suit;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $power;

    public function __construct($name, $suit, $power)
    {
        $this->name = $name;
        $this->suit = $suit;
        $this->power = $power;
    }

    public function __tostring()
    {
        return sprintf('%s of %ss', $this->name, $this->suit);
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

    public function setSuit($suit)
    {
        if (!in_array($suit, self::$validSuits)) {
            throw new \InvalidArgumentException(sprintf('%s is not a valid suit'));
        }

        $this->suit = $suit;
    }

    public function getSuit()
    {
        return $this->suit;
    }

    public function setPower($power)
    {
        $this->power = $power;
    }

    public function getPower()
    {
        return $this->power;
    }
}

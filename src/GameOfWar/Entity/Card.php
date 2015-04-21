<?php

namespace GameOfWar\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="CardRepository")
 * @ORM\Table(name="cards")
 *
 * The entity model for a card in a deck
 *
 * @version 1.01
 * @author Andre Jon Branchizio <andrejbranch@gmail.com>
 */
class Card
{
    /**
     * @var string the club suit type
     */
    const SUIT_CLUB = "Club";

    /**
     * @var string the diamond suit type
     */
    const SUIT_DIAMOND = "Diamond";

    /**
     * @var string the heart suit type
     */
    const SUIT_HEART = "Heart";

    /**
     * @var string the spade suit type
     */
    const SUIT_SPADE = "Spade";

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
     * @var int the cards id
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     * @var string the name of the card
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=8)
     * @var string the suit type of the card
     */
    private $suit;

    /**
     * @ORM\Column(type="integer", length=2)
     * @var int the relative power of the card
     */
    private $power;

    /**
     * Initializes a new Card
     *
     * @param string $name
     * @param string $suit
     * @param int $power
     */
    public function __construct($name, $suit, $power)
    {
        $this->name = $name;
        $this->suit = $suit;
        $this->power = $power;
    }

    /**
     * Converts the card object to a string value
     *
     * @return string
     */
    public function __tostring()
    {
        return sprintf('%s of %ss', $this->name, $this->suit);
    }

    /**
     * Set the id of the card
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the id of the card
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the name of the card
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the card
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the suit of the card
     *
     * @param string $suit
     * @throws InvalidArgumentException if the provided suit is not in the valid suits array
     */
    public function setSuit($suit)
    {
        if (!in_array($suit, self::$validSuits)) {
            throw new \InvalidArgumentException(sprintf('%s is not a valid suit'));
        }

        $this->suit = $suit;
    }

    /**
     * Get the suit of the card
     *
     * @return string
     */
    public function getSuit()
    {
        return $this->suit;
    }

    /**
     * Set the relative power of the card
     *
     * @param int $power
     */
    public function setPower($power)
    {
        $this->power = $power;
    }

    /**
     * Get the relative power of the card
     *
     * @return int
     */
    public function getPower()
    {
        return $this->power;
    }
}

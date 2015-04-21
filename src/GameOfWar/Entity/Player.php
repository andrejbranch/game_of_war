<?php

namespace GameOfWar\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="players")
 *
 * The entity model for a game of war player
 *
 * @author Andre Jon Branchizio <andrejbranch@gmail.com>
 */
class Player
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var int the players id
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string the players name
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="GameOfWar\Entity\PlayerCard", mappedBy="player")
     * @var Doctrine\Common\Collections\ArrayCollection the players deck of cards
     */
    protected $playerCards;

    /**
     * @var transient GameOfWar\Entity\PlayerCard
     * The players current PlayerCard in play (faceup)
     */
    protected $cardInPlay;

    /**
     * @var transient Doctrine\Common\Collections\ArrayCollection collection of PlayerCards
     * PlayerCards that either set aside or face down
     */
    protected $tableCards;

    /**
     * Initializes a new Player
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->playerCards = new ArrayCollection();
        $this->tableCards = new ArrayCollection();
    }

    /**
     * Set the players id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the players id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the players name
     *
     * @param type $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the players name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add a PlayerCard to players deck
     *
     * @param PlayerCard $playerCard
     */
    public function addPlayerCard(PlayerCard $playerCard)
    {
        $this->playerCards->add($playerCard);
    }

    /**
     * Remove a specified PlayerCard from the players deck
     *
     * @param PlayerCard $playerCard
     */
    public function removePlayerCard(PlayerCard $playerCard)
    {
        $this->playerCards->removeElement($playerCard);
    }

    /**
     * Get the players deck of cards
     *
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getPlayerCards()
    {
        return $this->playerCards;
    }

    /**
     * Get the players cards that are set aside or face down
     *
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getTableCards()
    {
        return $this->tableCards;
    }

    /**
     * Add a PlayerCard to the table cards collection
     *
     * @param PlayerCard $playerCard
     */
    public function addTableCard(PlayerCard $playerCard)
    {
        $this->removePlayerCard($playerCard);
        $this->tableCards->add($playerCard);
    }

    /**
     * Clear the players table card collection
     */
    public function removeTableCards()
    {
        return $this->tableCards->clear();
    }

    /**
     * Get the players top card
     *
     * @return PlayerCard
     */
    public function getTopPlayerCard()
    {
        return $this->playerCards->first();
    }

    /**
     * Count the players deck of cards
     *
     * @return int
     */
    public function getCardCount()
    {
        return $this->playerCards->count();
    }

    /**
     * Get the players face up card that is in play
     *
     * @return PlayerCard if the player has cards left, boolean if player is out of cards
     */
    public function getPlayerCardInPlay()
    {
        if (!$this->cardInPlay) {

            $topCard = $this->getTopPlayerCard();

            // no cards left
            if (!$topCard) {
                return false;
            }

            $this->setPlayerCardInPlay($this->getTopPlayerCard());

            return $this->cardInPlay;
        }

        return $this->cardInPlay;
    }

    /**
     * Set the players face up card in play
     *
     * @param PlayerCard $playerCardInPlay
     */
    public function setPlayerCardInPlay(PlayerCard $playerCardInPlay)
    {
        $this->cardInPlay = $playerCardInPlay;
        $this->removePlayerCard($this->cardInPlay);
    }

    /**
     * Unset the players current face up card in play
     */
    public function removeCardInPlay()
    {
        $this->cardInPlay = null;
    }

    /**
     * Sets aside the current card in play to the table cards collection
     * and then picks 1 of 3 new face down cards. The selected card is randomly
     * chosen and added to the current face up card in play. The remaining cards
     * are added to the players table cards collection.
     *
     * @return PlayerCard if player has cards to play, boolean if player is out of cards
     */
    public function pickWarPlayerCard()
    {
        // set the current card in play aside
        $this->tableCards->add($this->cardInPlay);
        $this->removeCardInPlay();

        $playerCardsCount = $this->playerCards->count();

        // I have no cards left so I lose :(
        if ($playerCardsCount == 0) {
            $this->cardInPlay = null;
            return false;
        }

        // I dont have enough cards to put 3 down so I have to turn over the last
        if ($playerCardsCount < 3) {
            $i = 1;
            foreach ($this->playerCards as $playerCard) {
                if ($i == $playerCardsCount) {
                    $this->setPlayerCardInPlay($playerCard);
                } else {
                    $this->addTableCard($playerCard);
                }

                $i++;
            }

            return $this->cardInPlay;
        }

        // pick 1 of the 3 cards
        $randomSelection = rand(1, 3);
        $i = 1;
        foreach ($this->playerCards as $playerCard) {

            if ($i == $randomSelection) {
                $this->setPlayerCardInPlay($playerCard);
            } elseif ($i > 3) {
                break;
            } else {
                $this->addTableCard($playerCard);
            }

            $i++;
        }

        return $this->cardInPlay;
    }
}

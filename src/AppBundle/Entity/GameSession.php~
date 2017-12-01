<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GameSession
 *
 * @ORM\Table(name="game_session")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GameSessionRepository")
 */
class GameSession
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Many GameSessions have One Center
     * @ORM\ManyToOne(targetEntity="Center")
     * @ORM\JoinColumn(name="center_id", referencedColumnName="id")
     */
    private $center;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * Many GameSession have Many Cards.
     * @ORM\ManyToMany(targetEntity="Card", mappedBy="gameSessions", cascade={"persist"})
     */
    private $cards;

    /**
     * One GameSession has Many GameScores.
     * @ORM\OneToMany(targetEntity="GameScore", mappedBy="gameSession", cascade={"persist"})
     */
    private $gameScores;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cards = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gameScores = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return GameSession
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set center
     *
     * @param \AppBundle\Entity\Center $center
     *
     * @return GameSession
     */
    public function setCenter(\AppBundle\Entity\Center $center = null)
    {
        $this->center = $center;

        return $this;
    }

    /**
     * Get center
     *
     * @return \AppBundle\Entity\Center
     */
    public function getCenter()
    {
        return $this->center;
    }

    /**
     * Add card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return GameSession
     */
    public function addCard(\AppBundle\Entity\Card $card)
    {
        $this->cards[] = $card;

        return $this;
    }

    /**
     * Remove card
     *
     * @param \AppBundle\Entity\Card $card
     */
    public function removeCard(\AppBundle\Entity\Card $card)
    {
        $this->cards->removeElement($card);
    }

    /**
     * Get cards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * Add gameScore
     *
     * @param \AppBundle\Entity\GameScore $gameScore
     *
     * @return GameSession
     */
    public function addGameScore(\AppBundle\Entity\GameScore $gameScore)
    {
        $this->gameScores[] = $gameScore;

        return $this;
    }

    /**
     * Remove gameScore
     *
     * @param \AppBundle\Entity\GameScore $gameScore
     */
    public function removeGameScore(\AppBundle\Entity\GameScore $gameScore)
    {
        $this->gameScores->removeElement($gameScore);
    }

    /**
     * Get gameScores
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGameScores()
    {
        return $this->gameScores;
    }

    /**
     * Get the number of players
     *
     * @return int
     */
    public function getNbPlayers()
    {
        return count($this->getGameScores());
    }
}

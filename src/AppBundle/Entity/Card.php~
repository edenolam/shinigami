<?php

namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Card
 *
 * @ORM\Table(name="card")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardRepository")
 */
class Card
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
     * @var int
     *
     * @ORM\Column(name="number", type="integer", unique=true)
	 * @Assert\Length(
	 *      min = 9,
	 *      max = 9,
	 *      minMessage = "the card number must be at least {{ limit }} characters long",
	 *      maxMessage = "the card number cannot be longer than {{ limit }} characters"
	 * )
     */
    private $number;

    /**
     * Many cards have one center
     * @ORM\ManyToOne(targetEntity="Center", inversedBy="cards")
     * @ORM\JoinColumn(name="center_id", referencedColumnName="id")
     */
    private $center;

    /**
     * One Card has One Customer.
     * @ORM\OneToOne(targetEntity="Customer", inversedBy="card")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    private $customer;

    /**
     * @var int
     *
     * @ORM\Column(name="visits", type="integer")
     */
    private $visits;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;

    /**
     *
     * @ORM\OneToMany(targetEntity="CardsOffers", mappedBy="card")
     */
    private $cardsOffers;

	/**
	 * @var bool
	 *
	 * @ORM\Column(name="given", type="boolean")
	 */
    private $given;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;


    /**
     * Many Cards have Many GameSessions.
     * @ORM\ManyToMany(targetEntity="GameSession", inversedBy="cards", cascade={"persist"})
     * @ORM\JoinTable(name="cards_gameSessions")
     */
    private $gameSessions;

    /**
     * One Card has Many GameScores.
     * @ORM\OneToMany(targetEntity="GameScore", mappedBy="card")
     */
    private $gameScores;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->offers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gameSessions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set number
     *
     * @param integer $number
     *
     * @return Card
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set visits
     *
     * @param integer $visits
     *
     * @return Card
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;

        return $this;
    }

    /**
     * Get visits
     *
     * @return integer
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return Card
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Card
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set center
     *
     * @param \AppBundle\Entity\Center $center
     *
     * @return Card
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
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     *
     * @return Card
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Add cardsOffer
     *
     * @param \AppBundle\Entity\cardsOffers $cardsOffer
     *
     * @return Card
     */
    public function addCardsOffer(\AppBundle\Entity\cardsOffers $cardsOffer)
    {
        $this->cardsOffers[] = $cardsOffer;

        return $this;
    }

    /**
     * Remove cardsOffer
     *
     * @param \AppBundle\Entity\cardsOffers $cardsOffer
     */
    public function removeCardsOffer(\AppBundle\Entity\cardsOffers $cardsOffer)
    {
        $this->cardsOffers->removeElement($cardsOffer);
    }

    /**
     * Get cardsOffers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCardsOffers()
    {
        return $this->cardsOffers;
    }

    /**
     * Add gameSession
     *
     * @param \AppBundle\Entity\GameSession $gameSession
     *
     * @return Card
     */
    public function addGameSession(\AppBundle\Entity\GameSession $gameSession)
    {
        $this->gameSessions[] = $gameSession;

        return $this;
    }

    /**
     * Remove gameSession
     *
     * @param \AppBundle\Entity\GameSession $gameSession
     */
    public function removeGameSession(\AppBundle\Entity\GameSession $gameSession)
    {
        $this->gameSessions->removeElement($gameSession);
    }

    /**
     * Get gameSessions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGameSessions()
    {
        return $this->gameSessions;
    }

    /**
     * Add gameScore
     *
     * @param \AppBundle\Entity\GameScore $gameScore
     *
     * @return Card
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
     * Set given
     *
     * @param boolean $given
     *
     * @return Card
     */
    public function setGiven($given)
    {
        $this->given = $given;

        return $this;
    }

    /**
     * Get given
     *
     * @return boolean
     */
    public function getGiven()
    {
        return $this->given;
    }
}

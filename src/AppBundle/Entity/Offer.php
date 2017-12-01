<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Offer
 *
 * @ORM\Table(name="offer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OfferRepository")
 */
class Offer
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
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @var int
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="offerType", type="string", length=255)
     */
    private $offerType;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var int
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="CardsOffers", mappedBy="offer")
     */
    private $cardsOffers;



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
     * Set count
     *
     * @param integer $count
     *
     * @return Offer
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set offerType
     *
     * @param string $offerType
     *
     * @return Offer
     */
    public function setOfferType($offerType)
    {
        $this->offerType = $offerType;

        return $this;
    }

    /**
     * Get offerType
     *
     * @return string
     */
    public function getOfferType()
    {
        return $this->offerType;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Offer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Offer
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Offer
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cardsOffers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cardsOffer
     *
     * @param \AppBundle\Entity\CardsOffers $cardsOffer
     *
     * @return Offer
     */
    public function addCardsOffer(\AppBundle\Entity\CardsOffers $cardsOffer)
    {
        $this->cardsOffers[] = $cardsOffer;

        return $this;
    }

    /**
     * Remove cardsOffer
     *
     * @param \AppBundle\Entity\CardsOffers $cardsOffer
     */
    public function removeCardsOffer(\AppBundle\Entity\CardsOffers $cardsOffer)
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
}

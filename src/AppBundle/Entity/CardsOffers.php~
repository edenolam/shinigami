<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CardsOffers
 *
 * @ORM\Table(name="cards_offers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardOffersRepository")
 */
class CardsOffers
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
     *
     * @ORM\ManyToOne(targetEntity="Card", inversedBy="cardsOffers")
     * @ORM\JoinColumn(name="card_id", nullable=false)
     */
    private $card;

    /**
     * @ORM\ManyToOne(targetEntity="Offer", inversedBy="cardsOffers")
     * @ORM\JoinColumn(name="offer_id", nullable=false)
     */
    private $offer;

    /**
     * @var bool
     *
     * @ORM\Column(name="used", type="boolean")
     */
    private $used;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set card
     *
     * @param \stdClass $card
     *
     * @return CardsOffers
     */
    public function setCard($card)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return \stdClass
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Set offer
     *
     * @param \stdClass $offer
     *
     * @return CardsOffers
     */
    public function setOffer($offer)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return \stdClass
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Set used
     *
     * @param boolean $used
     *
     * @return CardsOffers
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return bool
     */
    public function getUsed()
    {
        return $this->used;
    }
}

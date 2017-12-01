<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 30/11/2017
 * Time: 16:34
 */

namespace AppBundle\Event;


use AppBundle\Entity\Card;
use AppBundle\Entity\Offer;
use Symfony\Component\EventDispatcher\Event;

class AddOfferToCustomerCardEvent extends Event
{

    const NAME = 'app.add_offer_to_customer_card.event';

    /**
     * @var Offer
     */
    private $offer;

    /**
     * @var Card
     */
    private $card;

    public function __construct(Offer $offer, Card $card)
    {
        $this->offer = $offer;
        $this->card = $card;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @return Offer
     */
    public function getOffer()
    {
        return $this->offer;
    }

}
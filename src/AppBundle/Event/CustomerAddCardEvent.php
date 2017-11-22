<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 21/11/2017
 * Time: 12:17
 */

namespace AppBundle\Event;


use AppBundle\Entity\Card;
use Symfony\Component\EventDispatcher\Event;

class CustomerAddCardEvent extends Event
{
    const NAME = 'app.customer_add_card.event';

    /**
     * @var Card
     */
    private $card;

    /**
     * CustomerAddCardEvent constructor.
     */
    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }
}
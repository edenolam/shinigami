<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 21/11/2017
 * Time: 15:25
 */

namespace AppBundle\Event;


use AppBundle\Entity\Card;
use Symfony\Component\EventDispatcher\Event;

class CustomerStatsUpdateEvent extends Event
{

    const NAME = 'app.customer_update_stats.event';

    /**
     * @var Card
     */
    private $card;

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
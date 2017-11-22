<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 20/11/2017
 * Time: 11:29
 */

namespace AppBundle\Listener;


use AppBundle\Event\CustomerStatsUpdateEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

class GameSessionListener
{

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     *
     * Updates Visits and Total Score of the cards
     *
     * @param $event
     */
    public function onGameSessionCreation($event)
    {
        $gameSession = $event->getGameSession();

        foreach ($gameSession->getGameScores() as $gameScore){
            $card = $gameScore->getCard();
            if($card){
                $card->setVisits($card->getVisits() + 1);
                $card->setScore($card->getScore() + $gameScore->getScore());
                $event = new CustomerStatsUpdateEvent($card);
                $this->dispatcher->dispatch($event::NAME, $event);
            }
        }
    }
}
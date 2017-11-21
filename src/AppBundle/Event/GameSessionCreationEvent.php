<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 20/11/2017
 * Time: 11:21
 */

namespace AppBundle\Event;


use AppBundle\Entity\GameSession;
use Symfony\Component\EventDispatcher\Event;

class GameSessionCreationEvent extends Event
{

    const NAME = "app.gamesession_created.event";

    /**
     * @var GameSession
     */
    private $gameSession;

    public function __construct(GameSession $gameSession)
    {
        $this->gameSession = $gameSession;
    }

    /**
     * @return GameSession
     */
    public function getGameSession()
    {
        return $this->gameSession;
    }


}
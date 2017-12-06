<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 17/11/2017
 * Time: 16:36
 */

namespace AppBundle\Service;


use AppBundle\Entity\Card;
use AppBundle\Entity\GameSession;
use AppBundle\Event\GameSessionCreationEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GameSessionManager
{

    /**
     * @var ObjectManager
     */
    private $entityManager;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(ObjectManager $entityManager, SessionInterface $session, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->dispatcher = $dispatcher;
    }


    /**
     * Creates a Game Session
     *
     * @param $request
     * @param GameSession $gameSession
     * @return GameSession|null
     */
    public function gameSessionCreation($request, GameSession $gameSession)
    {
        $hydratedGameSession = $this->gameSessionsHydratation($request, $gameSession);

        if($hydratedGameSession){
            // Dispatch Game Session creation event
            $event = new GameSessionCreationEvent($gameSession);
            $this->dispatcher->dispatch(GameSessionCreationEvent::NAME, $event);
        }

        return $hydratedGameSession;
    }

    /**
     * Saves a Game Session in the database
     *
     * @param GameSession $gameSession
     */
    public function save(GameSession $gameSession)
    {
        $this->entityManager->persist($gameSession);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('success', 'The game session has been saved !');
    }


    /**
     * Hydrates a Game Session Object
     *
     * @param $request
     * @param GameSession $gameSession
     * @return GameSession|null
     */
    private function gameSessionsHydratation($request, GameSession $gameSession)
    {
        // Hydratation of unmapped datas (cards, datetime)
        $data = $request->request->get('appbundle_gamesession');

        // Adding cards to Gamescores
        $i = 0;
        foreach($data["gameScores"] as $gameScore){
            $gameSession->getGameScores()[$i]->setGameSession($gameSession);
            if($gameScore["card"]){
                $card = $this->entityManager->getRepository('AppBundle:Card')->findValidCardByNumber($gameScore["card"]);
                if($card != null){
                    $this->addCardNumberToGameScore($card, $gameSession, $i);
                }else{
                    $this->session->getFlashBag()->add('error', "The number card ".$gameScore["card"]." is not valid.");
                    return null;
                }
            }
            $i++;
        }
        // Adding datetime to Gamesession
        $this->setDateTimeToGameSession($data, $gameSession);

        return $gameSession;
    }

    /**
     * Link cards numbers of the players to the Game Session
     *
     * @param Card $card
     * @param GameSession $gameSession
     * @param $gameScoreKey
     */
    private function addCardNumberToGameScore(Card $card, GameSession $gameSession, $gameScoreKey)
    {

        $card->addGameSession($gameSession);
        $gameSession->getGameScores()[$gameScoreKey]->setCard($card);
        $gameSession->addCard($card);

        $this->entityManager->persist($card);

    }

    /**
     * Sets the date of a Game Session
     *
     * @param $data
     * @param GameSession $gameSession
     */
    private function setDateTimeToGameSession($data, GameSession $gameSession)
    {
        $date = $data['date'];
        $date = str_replace(",", "", $date);
        $time = $data['time'];
        $datetime = new \DateTime("$date $time");
        $gameSession->setDate($datetime);
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 17/11/2017
 * Time: 16:36
 */

namespace AppBundle\Service;


use AppBundle\Event\GameSessionCreationEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

class GameSessionManager
{

    /**
     * @var ObjectManager
     */
    private $entityManager;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(ObjectManager $entityManager, Session $session, EventDispatcher $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->dispatcher = $dispatcher;
    }


    public function gameSessionCreation($request, $gameSession)
    {
        $hydratedGameSession = $this->gameSessionsHydratation($request, $gameSession);

        if($hydratedGameSession){
            // Dispatch Game Session creation event
            $event = new GameSessionCreationEvent($gameSession);
            $this->dispatcher->dispatch(GameSessionCreationEvent::NAME, $event);
        }

        return $hydratedGameSession;
    }

    public function save($gameSession)
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->entityManager->persist($gameSession);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('success', 'The game session has been saved !');
    }

    private function gameSessionsHydratation($request, $gameSession)
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

    private function addCardNumberToGameScore($card, $gameSession, $gameScoreKey)
    {

            $card->addGameSession($gameSession);
            $gameSession->getGameScores()[$gameScoreKey]->setCard($card);
            $gameSession->addCard($card);

            $this->entityManager->persist($card);

    }

    private function setDateTimeToGameSession($data, $gameSession)
    {
        $date = $data['date'];
        $date = str_replace(",", "", $date);
        $time = $data['time'];
        $datetime = new \DateTime("$date $time");
        $gameSession->setDate($datetime);
    }

}
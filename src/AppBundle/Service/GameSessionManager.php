<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 17/11/2017
 * Time: 16:36
 */

namespace AppBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
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

    public function __construct(ObjectManager $entityManager, Session $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function gameSessionsHydratation($request, $gameSession)
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
                    return;
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
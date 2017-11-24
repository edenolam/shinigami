<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 23/11/2017
 * Time: 13:00
 */

namespace AppBundle\Controller;


use AppBundle\Entity\GameSession;
use AppBundle\Form\GameSessionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StaffGameSessionController extends Controller
{
    /**
     * Creation of a Game Session
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $gameSessionManager = $this->get('app.gamesession.manager');
        $gameSession = new GameSession();
        $form = $this->createForm(GameSessionType::class, $gameSession);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $gameSession = $gameSessionManager->gameSessionCreation($request, $gameSession);
            if($gameSession instanceof GameSession){
                $gameSessionManager->save($gameSession);
                return $this->redirectToRoute('staff_search');
            }
        }

        return $this->render('staff/gameSessions/game_session.html.twig', array(
            "form" => $form->createView()
        ));
    }
}
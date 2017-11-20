<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\GameSession;
use AppBundle\Form\GameSessionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\SearchCustomerType;

class StaffController extends Controller
{
	public function panelAction(Request $request)
	{

		$form = $this->createForm(SearchCustomerType::class);

		$form->handleRequest($request);

		$entity_manager = $this->getDoctrine()->getManager();
		if ($request->isMethod('POST')){
			if ($form->isSubmitted() && $form->isValid()){
				$customerData = $form->getData();
				$customer = $entity_manager->getRepository('AppBundle:Customer')
					->findCustomerWithoutCard(
						$customerData["firstname"],
						$customerData["lastname"],
						$customerData['phone']
					);
			}else{
				$cardData = $request->get('search_field');
				$card = $entity_manager->getRepository('AppBundle:Card')
					->findValidCardByNumber($cardData);
				return $this->redirectToRoute('staff_card', [
					'number' => $card->getNumber()
				]);
			}
		}

		return $this->render('staff/panel.html.twig', array(
			'form' =>$form->createView()
		));
	}

	public function manageCardAction(Card $card)
	{
		$customer = $card->getCustomer();
		$em = $this->getDoctrine()->getManager();
		$gameSession = $em->getRepository("AppBundle:GameSession")->findGameSessionsOfCustomer($card);
		return $this->render('staff/card.html.twig',[
			'customer' => $customer,
			'card'     => $card,
			'gameSessions' => $gameSession,
			'offers'   => NULL
		]);
	}

    public function newGameSessionAction(Request $request)
    {
        $gameSession = new GameSession();
        $form = $this->createForm(GameSessionType::class, $gameSession);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $gameSession = $this->get('app.gamesession.manager')->gameSessionCreation($request, $gameSession);
            if($gameSession instanceof GameSession){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($gameSession);
                $entityManager->flush();
                $this->addFlash('success', 'The game session has been saved !');

                return $this->redirectToRoute('staff_panel');
            }
        }

        return $this->render('staff/game_session.html.twig', array(
            "form" => $form->createView()
        ));
    }

    public function offersListAction()
    {
        return $this->render('staff/offers_list.html.twig', array('offers' => null));
    }
}
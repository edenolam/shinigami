<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\GameSession;
use AppBundle\Form\CardType;
use AppBundle\Form\CustomerType;
use AppBundle\Entity\Offer;
use AppBundle\Form\GameSessionType;
use AppBundle\Form\OfferType;
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
			$card = null;
			if ($form->isSubmitted() && $form->isValid()){
				$customerData = $form->getData();
				$customer = $entity_manager->getRepository('AppBundle:Customer')
					->findCustomerWithoutCard(
						$customerData["firstname"],
						$customerData["lastname"],
						$customerData['phone']
					);
				if ($customer){
					$card = $customer->getCard();
				}
			}else{
				$cardData = $request->get('search_field');
				$card = $entity_manager->getRepository('AppBundle:Card')
					->findValidCardByNumber($cardData);
			}

			if ($card){
				return $this->redirectToRoute('staff_card', [
					'number' => $card->getNumber()
				]);
			}else{
				$this->addFlash('error', 'Card not found');
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




    public function editCardAction(Request $request, Card $card)
	{
		$form = $this->createForm(CardType::class, $card);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($card);
			$entityManager->flush();
			$this->addFlash('success', 'The card has been updated');
			return $this->redirectToRoute('staff_card', [
				'number' => $card->getNumber()
			]);
		}
		return $this->render('staff/edit_card.html.twig', array(
			"form" => $form->createView()
		));
	}

	public function editCustomerAction(Request $request, Card $card)
	{
		$form = $this->createForm(CustomerType::class, $card->getCustomer());
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			$em = $this->getDoctrine()->getManager();
			$em->persist($this->getUser()->getCustomer());
			$em->flush();

			$this->addFlash('success', "Your informations were modified.");

			return $this->redirectToRoute('staff_card');
		}

		return $this->render('customer/modify.html.twig', array(
			"form" => $form->createView()
		));
	}


    public function offersListAction()
    {
        return $this->render('staff/offers_list.html.twig', array('offers' => null));
    }

    public function offersCreateAction(Request $request)
    {

        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $offer->setIsActive(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();
            $this->addFlash('success', "The offer ".$offer->getName()." has been saved");
            return $this->redirectToRoute('staff_offers_list');
        }
        return $this->render('staff/offers_create.html.twig', array(
            "form" => $form->createView()
        ));
    }

}
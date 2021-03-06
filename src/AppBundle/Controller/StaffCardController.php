<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 27/11/2017
 * Time: 12:23
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Card;
use AppBundle\Form\CardGenerationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StaffCardController extends Controller
{

    public function newAction(Request $request)
    {
        $cardManager = $this->get('app.card.manager');

        $card = new Card();
        $form = $this->createForm(CardGenerationType::class, $card);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $request->request->get('appbundle_card');
            $cardNumber = $data['center'].$data['number'].$data['modulo'];
			if($cardManager->initCard($card, $cardNumber)){
				$cardManager->save($card, false);
			}
        }

        $cards = $cardManager->getCardsWithoutCustomer();


        return $this->render('staff/card/new.html.twig', array(
            "form" => $form->createView(),
            "cards" => $cards
        ));
    }


    public function generateAjaxAction(Request $request, $center)
    {
        $cardManager = $this->get('app.card.manager');
        $cardNumber = $cardManager->newCard($center);

        $response = new JsonResponse();
        $response->setData($cardNumber);
        return $response;

    }

    public function giveCardAjaxAction(Request $request, Card $card)
	{
		$cardManager = $this->get('app.card.manager');
		$cardManager->giveCard($card);
		$response = new JsonResponse();
		return $response;
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 15/11/2017
 * Time: 16:59
 */

namespace AppBundle\Controller;


use AppBundle\Form\CustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends Controller
{
    public function panelAction(Request $request)
    {
        $customer = $this->getUser()->getCustomer();
        $cardManager = $this->get('app.card.manager');

        if($request->isMethod("POST")){
            $cardManager->addCardToCustomer($request->get("number"), $customer);
        }

        $card = $customer->getCard();
        $cardsOffers = $cardManager->getValidCardsOffersOfCustomer($card);
        $lockedOffers = $cardManager->getLockedOffersOfCustomer($card);

        return $this->render('customer/panel.html.twig', array(
            "customer" => $customer,
            "card" => $card,
            "cardsOffers" => $cardsOffers,
            "lockedOffers" => $lockedOffers
        ));
    }

    public function modifyAction(Request $request)
    {
        $form = $this->createForm(CustomerType::class, $this->getUser()->getCustomer());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($this->getUser()->getCustomer());
            $em->flush();

            $this->addFlash('success', "Your informations were modified.");

            return $this->redirectToRoute('customer_panel');
        }

        return $this->render('customer/modify.html.twig', array(
            "form" => $form->createView()
        ));
    }
}
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
    /**
     * Customer's panel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
        $gameSessions = $cardManager->getGameSessionsOfCustomer($card);

        return $this->render('customer/panel.html.twig', array(
            "customer" => $customer,
            "card" => $card,
            "cardsOffers" => $cardsOffers,
            "lockedOffers" => $lockedOffers,
            "gameSessions" => $gameSessions
        ));
    }

    /**
     * Edit of the customer's informations
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function modifyAction(Request $request)
    {
        $form = $this->createForm(CustomerType::class, $this->getUser()->getCustomer());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $customer = $this->getUser()->getCustomer();
			$birthday = $request->request->get('appbundle_customer')['birthday'];
			$customer->setBirthday(new \DateTime($birthday));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();

            $this->addFlash('success', "Your informations were modified.");

            return $this->redirectToRoute('customer_panel');
        }

        return $this->render('customer/modify.html.twig', array(
            "form" => $form->createView(),
            "customer" => $this->getUser()->getCustomer()
        ));
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 23/11/2017
 * Time: 12:47
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Card;
use AppBundle\Entity\CardsOffers;
use AppBundle\Form\CardNumberType;
use AppBundle\Form\CustomerType;
use AppBundle\Form\SearchCustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StaffCustomerController extends Controller
{
    /**
     * Search a customer by his card number or by his firstname, lastname and phone number
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request)
    {
        $form = $this->createForm(SearchCustomerType::class);
        $form->handleRequest($request);
        $cardManager = $this->get('app.card.manager');

        if ($request->isMethod('POST')){
            $card = null;
            if ($form->isSubmitted() && $form->isValid()){
                $card = $cardManager->searchCustomerWithoutCard($form->getData());
            }else{
                $card = $cardManager->searchCustomerByCardNumber($request->get('search_field'));
            }

            if ($card){
                return $this->redirectToRoute('staff_customer_view', [
                    'number' => $card->getNumber()
                ]);
            }else{
                $this->addFlash('error', 'Card not found');
            }
        }

        return $this->render('staff/customer/search.html.twig', array(
            'form' =>$form->createView()
        ));
    }

    /**
     * A page with the datas of the customer
     *
     * @param Card $card
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cardViewAction(Card $card)
    {
        $cardManager = $this->get('app.card.manager');
        $gameSessions = $cardManager->getGameSessionsOfCustomer($card);
        $cardsOffers = $cardManager->getValidCardsOffersOfCustomer($card);
        $lockedOffers = $cardManager->getLockedOffersOfCustomer($card);

        return $this->render('staff/customer/view.html.twig',[
            'customer' => $card->getCustomer(),
            'card'     => $card,
            'gameSessions' => $gameSessions,
            'cardsOffers'   => $cardsOffers,
            "lockedOffers" => $lockedOffers
        ]);
    }

    /**
     * Edit the number of the customer's card
     *
     * @param Request $request
     * @param Card $card
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCardAction(Request $request, Card $card)
    {
        $cardManager = $this->get('app.card.manager');
        $form = $this->createForm(CardNumberType::class, $card);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $cardManager->save($card);
            return $this->redirectToRoute('staff_customer_view', [
                'number' => $card->getNumber()
            ]);
        }
        return $this->render('staff/customer/edit_card.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * Edit the datas of the customer
     *
     * @param Request $request
     * @param Card $card
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editCustomerAction(Request $request, Card $card)
    {
        $customer = $card->getCustomer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $birthday = $request->request->get('appbundle_customer')['birthday'];
            $customer->setBirthday(new \DateTime($birthday));
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();

            $this->addFlash('success', "The customer's informations were modified.");

            return $this->redirectToRoute('staff_customer_view', [
                'number' => $card->getNumber()
            ]);
        }

        return $this->render('staff/customer/edit_customer.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * Set to "used" the offers of the customer
     *
     * @param CardsOffers $cardOffer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function useCardOfferAction(CardsOffers $cardOffer, Request $request)
    {
        $cardOffer->setUsed(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cardOffer);
        $entityManager->flush();
        $this->addFlash('success', "The offer ".$cardOffer->getOffer()->getName()." has been used.");
        return $this->redirect($request->headers->get('referer'));
    }
}
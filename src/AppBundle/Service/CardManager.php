<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 16/11/2017
 * Time: 12:01
 */

namespace AppBundle\Service;


use AppBundle\Entity\Card;
use AppBundle\Event\CustomerAddCardEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Session\Session;

class CardManager
{

    private $entityManager;

    private $session;
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(ObjectManager $em, Session $session, EventDispatcher $dispatcher)
    {
        $this->entityManager = $em;
        $this->session = $session;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Gets the Card Entity Repository
     *
     * @return $repository The repository of Card Entity
     */
    private function getCardRepository()
    {
        $repository = $this->entityManager->getRepository('AppBundle:Card');
        return $repository;
    }

    /**
     * Add a card to a customer
     *
     * The card number must be an integer of 9 characters.
     * The card must be inactive and has no linked customer
     *
     * @param $number The card you want to add to the customer
     * @param $customer The customer
     */
    public function addCardToCustomer($number, $customer)
    {
        $number = intval($number);
        if($number){
            if(strlen((string)$number) != 9){
                $this->session->getFlashBag()->add('error', 'The card number your entered is not valid.');
                return;
            }
            $card = $this->getCardRepository()->findInactiveCardByNumber($number);

            if(null != $card){
                $card->setCustomer($customer);
                $card->setIsActive(true);
                $this->entityManager->persist($customer);
                $this->entityManager->persist($card);
                $this->entityManager->flush();
                $this->session->getFlashBag()->add('success', 'Success ! You have a new card !');
                $addCardEvent = new CustomerAddCardEvent($card);
                $this->dispatcher->dispatch(CustomerAddCardEvent::NAME, $addCardEvent);
				return;
            }else{
                $this->session->getFlashBag()->add('error', 'The card number your entered doesn\'t exist.');
                return;
            }
        }else{
            $this->session->getFlashBag()->add('error', 'The card number your entered is not valid.');
            return;
        }

    }

    public function getValidCardsOffersOfCustomer($card)
    {
        if($card){
            return $this->entityManager->getRepository("AppBundle:CardsOffers")->findValidCardsOffersOfCustomer($card);
        }else{
            return null;
        }
    }

    public function getLockedOffersOfCustomer($card)
    {
        return $this->entityManager->getRepository('AppBundle:Offer')->findLockedOffersForCustomer($card);
    }

    public function getGameSessionsOfCustomer($card)
    {
        return $this->entityManager->getRepository("AppBundle:GameSession")->findGameSessionsOfCustomer($card);
    }

    public function searchCustomerWithoutCard($data)
    {
        $customer = $this->entityManager->getRepository('AppBundle:Customer')
            ->findCustomerWithoutCard(
                $data["firstname"],
                $data["lastname"],
                $data['phone']
            );

        if($customer) {
            return $customer->getCard();
        }else{
            return null;
        }
    }

    public function searchCustomerByCardNumber($number)
    {
        return $this->getCardRepository()->findValidCardByNumber($number);
    }

    public function save($card){
        $this->entityManager->persist($card);
        $this->entityManager->flush();
        $this->session->getFlashBag()->add('success', 'The card has been updated');
    }
}
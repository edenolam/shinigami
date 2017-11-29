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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Session\Session;

class CardManager
{


    private $entityManager;

    private $session;
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * @var FormFactory
     */
    private $formFactory;

    public function __construct(ObjectManager $em, Session $session, EventDispatcher $dispatcher, FormFactory $formFactory)
    {
        $this->entityManager = $em;
        $this->session = $session;
        $this->dispatcher = $dispatcher;
        $this->formFactory = $formFactory;
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

    public function initCard(Card $card, $cardNumber)
    {
    	if($this->isAvailable($cardNumber)){
			$card->setNumber($cardNumber);
			$card->setIsActive(FALSE);
			$card->setGiven(FALSE);
			$card->setScore(0);
			$card->setVisits(0);
			return $card;
		}else{
    		$this->session->getFlashBag()->add('error', "This card is already in the database.");
    		return null;
		}
    }

    public function changeCardNumber($card, $number){
        $availableCard = $this->getCardRepository()->findOneByNumber($number);
        $this->entityManager->remove($availableCard);
        $this->entityManager->flush();
        $card->setNumber($number);
        $this->save($card);
    }

    public function getCardsWithoutCustomer()
    {
        return $this->getCardRepository()->findAllInactiveCards();
    }

    public function getChangeNumberCardForm(){
        $cards = $this->getCardsWithoutCustomer();
        $choices = array();
        foreach ($cards as $availableCard){
            $choices["Card n°".$availableCard->getNumber()] = $availableCard->getNumber();
        }

        $form = $this->formFactory->create()
            ->add('number', ChoiceType::class, array(
                "choices" => $choices,
                'attr' => array(
                    "class" => "browser-default"
                )
            ));

        return $form;
    }

    public function newCard($centerCode)
    {
        $cardNumber = $this->generateCardNumber($centerCode);

        while($this->isAvailable(implode("", $cardNumber)) === false){
            $cardNumber = $this->generateCardNumber($centerCode);
        }

        return $cardNumber;
    }


    private function generateCardNumber($centerCode)
    {
        $number = str_pad(rand(0,99999), 5, "0", STR_PAD_LEFT);
        $modulo =  $number % $centerCode;
        $cardNumber = array(
            "center" => $centerCode,
            "number" => $number,
            "modulo" => strval($modulo)[0]
        );

        return $cardNumber;
    }

    private function isAvailable($cardNumber)
    {
        if($this->getCardRepository()->findByNumber($cardNumber)) {
            return false;
        }else{
            return true;
        }
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

    public function giveCard(Card $card)
	{
		$card->setGiven(true);
		$this->save($card);
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
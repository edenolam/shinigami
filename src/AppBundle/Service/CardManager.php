<?php

namespace AppBundle\Service;


use AppBundle\Entity\Card;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Event\CustomerAddCardEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardManager
{

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(ObjectManager $em, SessionInterface $session, EventDispatcherInterface $dispatcher, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $em;
        $this->session = $session;
        $this->dispatcher = $dispatcher;
        $this->formFactory = $formFactory;
    }

    /**
     * Gets the Card entity Repository
     *
     * @return \AppBundle\Repository\CardRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function getCardRepository()
    {
        $repository = $this->entityManager->getRepository('AppBundle:Card');
        return $repository;
    }

    /**
     * Initialize a card
     *
     * @param Card $card
     * @param $cardNumber
     * @return Card|null
     */
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

    /**
     * Changes a card's number
     *
     * @param $card
     * @param $number
     */
    public function changeCardNumber($card, $number){
        $availableCard = $this->getCardRepository()->findOneByNumber($number);
        $this->entityManager->remove($availableCard);
        $this->entityManager->flush();
        $card->setNumber($number);
        $this->save($card, true);
    }

    /**
     * Looks for cards which are not related to a customer
     *
     * @return array
     */
    public function getCardsWithoutCustomer()
    {
        return $this->getCardRepository()->findAllInactiveCards();
    }

    /**
     * Creates a form for card number change
     *
     * @return \Symfony\Component\Form\FormInterface
     */
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


    /**
     * Creates a new card with generated number
     *
     * @param $centerCode
     * @return array
     */
    public function newCard($centerCode)
    {
        $cardNumber = $this->generateCardNumber($centerCode);

        while($this->isAvailable(implode("", $cardNumber)) === false){
            $cardNumber = $this->generateCardNumber($centerCode);
        }

        return $cardNumber;
    }

    /**
     * Generates a card number
     *
     * @param $centerCode
     * @return array
     */
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

    /**
     * Verify if the card number isn't already used in the database
     *
     * @param $cardNumber
     * @return bool
     */
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

    /**
     * Sets a Card to the status "given"
     *
     * @param Card $card
     */
    public function giveCard(Card $card)
	{
		$card->setGiven(true);
		$this->save($card, false);
	}



    /**
     * Gets all the Game Sessions of a customer
     *
     * @param $card
     * @return array
     */
    public function getGameSessionsOfCustomer($card)
    {
        return $this->entityManager->getRepository("AppBundle:GameSession")->findGameSessionsOfCustomer($card);
    }

    /**
     * Finds a customer card by the customer's firstname, lastname and phone number
     *
     * @param $data
     * @return null
     */
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

    /**
     * Finds a customer's card by his card's number
     *
     * @param $number
     * @return mixed
     */
    public function searchCustomerByCardNumber($number)
    {
        return $this->getCardRepository()->findValidCardByNumber($number);
    }

    /**
     * Saves the modification of a Card in the database
     *
     * @param $card
     */
    public function save($card, $message){
        $this->entityManager->persist($card);
        $this->entityManager->flush();
        if($message){
            $this->session->getFlashBag()->add('success', 'The card has been updated');
        }
    }
}
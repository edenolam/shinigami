<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 16/11/2017
 * Time: 12:01
 */

namespace AppBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;

class CardManager
{

    private $entityManager;

    private $session;

    public function __construct(ObjectManager $em, Session $session)
    {
        $this->entityManager = $em;
        $this->session = $session;
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
     * The card number must be an integer of 10 characters.
     * The card must be inactive and has no linked customer
     *
     * @param $number The card you want to add to the customer
     * @param $customer The customer
     */
    public function addCardToCustomer($number, $customer)
    {
        $number = intval($number);
//exit(dump($number));
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

}
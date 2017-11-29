<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 29/11/2017
 * Time: 11:22
 */

namespace AppBundle\Service;


use AppBundle\Entity\Card;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;

class OfferManager
{
    /**
     * @var ObjectManager
     */
    private $entityManager;
    /**
     * @var Session
     */
    private $session;

    public function __construct(ObjectManager $entityManager, Session $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    /**
     * Gets the repository of Offer entity
     *
     * @return \AppBundle\Repository\OfferRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getOffersRepository()
    {
        return $this->entityManager->getRepository('AppBundle:Offer');
    }

    /**
     * Adds a list of offers to a customer's card
     *
     * @param $offers An array of Offer objects
     * @param $card A Card object
     * @param $flash true or false Enable the adding of a flashbag
     */
    public function addOffersToCustomerCard($offers, Card $card, $flash)
    {
        foreach ($offers as $offer){
            $cardOffer = new CardsOffers();
            $cardOffer->setCard($card);
            $cardOffer->setOffer($offer);
            $cardOffer->setUsed(false);
            $this->entityManager->persist($cardOffer);
            $card->addCardsOffer($cardOffer);
        }
        $this->entityManager->persist($card);
        $this->entityManager->flush();
        if($flash) {
            $this->newOfferFlashMessage($offers);
        }
    }

    /**
     * Looks for available "visits" and "score" for a customer and adds them to his card
     *
     * @param $card a Card object
     */
    public function updateStatsOffers($card)
    {
        $offers = $this->findStatsOffers($card);
        $this->addOffersToCustomerCard($offers, $card, false);
    }

    /**
     * Adds a flashbag for the adding of an offer
     *
     * @param $offers An array of Offer objects
     */
    private function newOfferFlashMessage($offers)
    {
        foreach($offers as $offer){
            $this->session->getFlashBag()->add('success', "Hey ! You can now use the offer ".$offer->getName()." !");
        }
    }

    /**
     *
     * Finds the "visits" and "score" offers which are available for one customer
     *
     * @param $card a Card object
     * @return array an array of Offer objects which are available for one customer
     */
    private function findStatsOffers($card)
    {
        $visitsOffers = $this->getOffersRepository()->findUsableOffersByCustomer("visits", $card, $card->getVisits());
        $scoreOffers = $this->getOffersRepository()->findUsableOffersByCustomer("score", $card, $card->getScore());
        return $usableOffers = array_merge($visitsOffers, $scoreOffers);
    }
}
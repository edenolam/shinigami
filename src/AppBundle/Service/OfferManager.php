<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 29/11/2017
 * Time: 11:22
 */

namespace AppBundle\Service;


use AppBundle\Entity\Card;
use AppBundle\Entity\CardsOffers;
use AppBundle\Event\AddOfferToCustomerCardEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
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
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(ObjectManager $entityManager, Session $session, EventDispatcher $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->dispatcher = $dispatcher;
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

            $addOfferToCustomerCardEvent = new AddOfferToCustomerCardEvent($offer, $card);
            $this->dispatcher->dispatch($addOfferToCustomerCardEvent::NAME, $addOfferToCustomerCardEvent);

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
    public function updateStatsOffers(Card $card)
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
     * Finds the "visits" and "score" offers which are available for one customer
     *
     * @param Card $card
     * @return array
     */
    private function findStatsOffers(Card $card)
    {
        $visitsOffers = $this->getOffersRepository()->findUsableOffersByCustomer("visits", $card, $card->getVisits());
        $scoreOffers = $this->getOffersRepository()->findUsableOffersByCustomer("score", $card, $card->getScore());
        return array_merge($visitsOffers, $scoreOffers);
    }
}
<?php

namespace AppBundle\Service;


use AppBundle\Entity\Card;
use AppBundle\Entity\CardsOffers;
use AppBundle\Event\AddOfferToCustomerCardEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OfferManager
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

    public function __construct(ObjectManager $entityManager, SessionInterface $session, EventDispatcherInterface $dispatcher)
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
     * @param $offers
     * @param $card
     * @param $flash true or false Enable the adding of a flashbag
     */
    public function addOffersToCustomerCard($offers, Card $card, $flash)
    {
        foreach ($offers as $offer){
            if(!$this->isUnlockedByCustomer($offer, $card)){
                $cardOffer = new CardsOffers();
                $cardOffer->setCard($card);
                $cardOffer->setOffer($offer);
                $cardOffer->setUsed(false);
                $this->entityManager->persist($cardOffer);
                $card->addCardsOffer($cardOffer);

                $addOfferToCustomerCardEvent = new AddOfferToCustomerCardEvent($offer, $card);
                $this->dispatcher->dispatch($addOfferToCustomerCardEvent::NAME, $addOfferToCustomerCardEvent);
            }
        }
        $this->entityManager->persist($card);
        $this->entityManager->flush();
        if($flash) {
            $this->newOfferFlashMessage($offers);
        }
    }

    /**
     * Adds a flashbag for the adding of an offer
     *
     * @param $offers
     */
    private function newOfferFlashMessage($offers)
    {
        foreach($offers as $offer){
            $this->session->getFlashBag()->add('success', "Hey ! You can now use the offer ".$offer->getName()." !");
        }
    }

    /**
     * Looks for available "visits" and "score" for a customer and adds them to his card
     *
     * @param $card
     */
    public function updateStatsOffers(Card $card)
    {
        $offers = $this->findStatsOffers($card);
        $this->addOffersToCustomerCard($offers, $card, false);
    }

    public function useTempOffer($offerid, $cardid)
    {
        $offer = $this->getOffersRepository()->find($offerid);
        $card = $this->entityManager->getRepository('AppBundle:Card')->find($cardid);
        if(!$this->isUnlockedByCustomer($offer, $card)){
            $cardOffer = new CardsOffers();
            $cardOffer->setOffer($offer);
            $cardOffer->setCard($card);
            $cardOffer->setUsed(true);
            $this->entityManager->persist($cardOffer);
            $this->entityManager->flush();
            $this->session->getFlashBag()->add('success', "The offer ".$offer->getName()." has been used.");
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

    /**
     * Verify if an offer is already unlocked by a customer
     *
     * @param $offer
     * @param $card
     * @return CardsOffers|null|object
     */
    private function isUnlockedByCustomer($offer, $card)
    {
        return $this->entityManager->getRepository('AppBundle:CardsOffers')->findOneBy(array("offer"=>$offer, "card" => $card));

    }

    /**
     * Gets all the valid Offers for a customer
     *
     * @param $card
     * @return array|null
     */
    public function getValidCardsOffersOfCustomer($card)
    {
        if($card){
            return $this->entityManager->getRepository("AppBundle:CardsOffers")->findValidCardsOffersOfCustomer($card);
        }else{
            return null;
        }
    }

    /**
     * Gets all the locked Offers for a customer
     *
     * @param $card
     * @return array
     */
    public function getLockedOffersOfCustomer($card)
    {
        return $this->entityManager->getRepository('AppBundle:Offer')->findLockedOffersForCustomer($card);
    }

    public function getCurrentTempOffers($card)
    {
        return $this->getOffersRepository()->findUnusedCurrentTempOffers($card);
    }
}
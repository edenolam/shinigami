<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 21/11/2017
 * Time: 12:21
 */

namespace AppBundle\Listener;


use AppBundle\Entity\CardsOffers;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;

class OfferListener
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
     * Adds "welcome" offers
     *
     * @param $event
     */
    public function onCustomerAddCard($event)
    {
        $card = $event->getCard();
        // "welcome" offers
        $repository = $this->getOffersRepository();
        $offers = $repository->findByOfferType('welcome');

        $this->addOffersToCustomerCard($offers, $card);
    }

    public function onCustomerUpdateStats($event)
    {
        $card = $event->getCard();
        $this->updateStatsOffers($card);
    }

    private function getOffersRepository()
    {
        return $this->entityManager->getRepository('AppBundle:Offer');
    }

    private function newOfferFlashMessage($offers)
    {
        foreach($offers as $offer){
            $this->session->getFlashBag()->add('success', "Hey ! You can now use the offer ".$offer->getName()." !");
        }
    }

    private function addOffersToCustomerCard($offers, $card)
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
        $this->newOfferFlashMessage($offers);
    }

    private function updateStatsOffers($card)
    {
        $visitsOffers = $this->getOffersRepository()->findUsableOffersByCustomer("visits", $card, $card->getVisits());
        $scoreOffers = $this->getOffersRepository()->findUsableOffersByCustomer("score", $card, $card->getScore());
        $usableOffers = array_merge($visitsOffers, $scoreOffers);
        $this->addOffersToCustomerCard($usableOffers, $card);
    }

}
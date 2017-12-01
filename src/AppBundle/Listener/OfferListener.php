<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 21/11/2017
 * Time: 12:21
 */

namespace AppBundle\Listener;



use AppBundle\Service\NewsletterManager;
use AppBundle\Service\OfferManager;

class OfferListener
{

    /**
     * @var OfferManager
     */
    private $offerManager;
    /**
     * @var NewsletterManager
     */
    private $newsletterManager;

    public function __construct(OfferManager $offerManager, NewsletterManager $newsletterManager)
{
    $this->offerManager = $offerManager;
    $this->newsletterManager = $newsletterManager;
}

    /**
     * Adds "welcome" offers to customer's card when he adds his first card to his account
     *
     * @param $event Contains the costumer's card
     */
    public function onCustomerAddCard($event)
    {
        $card = $event->getCard();
        // "welcome" offers
        $repository = $this->offerManager->getOffersRepository();
        $offers = $repository->findByOfferType('welcome');

        $this->offerManager->addOffersToCustomerCard($offers, $card, true);
    }

    /**
     * Adds "visits" and "score" offers to customer's card every time he plays a game session
     *
     * @param $event Contains the costumer's card
     */
    public function onCustomerUpdateStats($event)
    {
        $card = $event->getCard();
        $this->offerManager->updateStatsOffers($card);
    }


    public function onAddOfferToCustomerCard($event){
        $card = $event->getCard();
        $offer = $event->getOffer();
        $customer = $card->getCustomer();

        $this->newsletterManager->sendNewOfferEmail($customer, $card, $offer);
    }

}
<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Entity\CardsOffers;
use AppBundle\Entity\GameSession;
use AppBundle\Entity\Newsletter;
use AppBundle\Form\CardType;
use AppBundle\Form\CustomerType;
use AppBundle\Entity\Offer;
use AppBundle\Form\GameSessionType;
use AppBundle\Form\NewsletterType;
use AppBundle\Form\OfferType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\SearchCustomerType;
use Symfony\Component\Validator\Constraints\DateTime;

class StaffController extends Controller
{
	public function panelAction(Request $request)
	{
		$form = $this->createForm(SearchCustomerType::class);
		$form->handleRequest($request);
		$entity_manager = $this->getDoctrine()->getManager();
		if ($request->isMethod('POST')){
			$card = null;
			if ($form->isSubmitted() && $form->isValid()){
				$customerData = $form->getData();
				$customer = $entity_manager->getRepository('AppBundle:Customer')
					->findCustomerWithoutCard(
						$customerData["firstname"],
						$customerData["lastname"],
						$customerData['phone']
					);
				if ($customer){
					$card = $customer->getCard();
				}
			}else{
				$cardData = $request->get('search_field');
				$card = $entity_manager->getRepository('AppBundle:Card')
					->findValidCardByNumber($cardData);
			}

			if ($card){
				return $this->redirectToRoute('staff_card', [
					'number' => $card->getNumber()
				]);
			}else{
				$this->addFlash('error', 'Card not found');
			}
		}

		return $this->render('staff/panel.html.twig', array(
			'form' =>$form->createView()
		));
	}

    public function manageCardAction(Card $card)
	{
		$customer = $card->getCustomer();
		$em = $this->getDoctrine()->getManager();
		$gameSession = $em->getRepository("AppBundle:GameSession")->findGameSessionsOfCustomer($card);
		$cardManager = $this->get('app.card.manager');
        $cardsOffers = $cardManager->getValidCardsOffersOfCustomer($card);
        $lockedOffers = $cardManager->getLockedOffersOfCustomer($card);
		return $this->render('staff/card.html.twig',[
			'customer' => $customer,
			'card'     => $card,
			'gameSessions' => $gameSession,
			'cardsOffers'   => $cardsOffers,
            "lockedOffers" => $lockedOffers
		]);
	}

    public function useCardOfferAction(CardsOffers $cardOffer, Request $request)
    {
        $cardOffer->setUsed(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($cardOffer);
        $entityManager->flush();
        $this->addFlash('success', "The offer ".$cardOffer->getOffer()->getName()." has been used.");
        return $this->redirect($request->headers->get('referer'));
    }

    public function newGameSessionAction(Request $request)
    {
        $gameSession = new GameSession();
        $form = $this->createForm(GameSessionType::class, $gameSession);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $gameSession = $this->get('app.gamesession.manager')->gameSessionCreation($request, $gameSession);
            if($gameSession instanceof GameSession){
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($gameSession);
                $entityManager->flush();
                $this->addFlash('success', 'The game session has been saved !');

                return $this->redirectToRoute('staff_panel');
            }
        }

        return $this->render('staff/game_session.html.twig', array(
            "form" => $form->createView()
        ));
    }

    public function editCardAction(Request $request, Card $card)
	{
		$form = $this->createForm(CardType::class, $card);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($card);
			$entityManager->flush();
			$this->addFlash('success', 'The card has been updated');
			return $this->redirectToRoute('staff_card', [
				'number' => $card->getNumber()
			]);
		}
		return $this->render('staff/edit_card.html.twig', array(
			"form" => $form->createView()
		));
	}

	public function editCustomerAction(Request $request, Card $card)
	{
		$form = $this->createForm(CustomerType::class, $card->getCustomer());
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			$birthday = $request->request->get('appbundle_customer')['birthday'];
			//exit(dump($birthday));
			$anniv = new \DateTime($birthday);
			$card->getCustomer()->setBirthday($anniv);
			$em = $this->getDoctrine()->getManager();
			$em->persist($this->getUser()->getCustomer());
			$em->flush();

			$this->addFlash('success', "Your informations were modified.");

			return $this->redirectToRoute('staff_card', [
				'number' => $card->getNumber()
			]);
		}

		return $this->render('customer/modify.html.twig', array(
			"form" => $form->createView()
		));
	}



    public function offersListAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $offers = $entityManager->getRepository("AppBundle:Offer")->findAll();
        return $this->render('staff/offers_list.html.twig', array('offers' => $offers));
    }

    public function offersCreateAction(Request $request)
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $offer->setIsActive(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();
            $this->addFlash('success', "The offer ".$offer->getName()." has been saved");
            return $this->redirectToRoute('staff_offers_list');
        }
        return $this->render('staff/offers_create.html.twig', array(
            "form" => $form->createView()
        ));
    }

    public function newsletterCreateAction(Request $request)
	{
		$newsletter = new Newsletter();
		$form = $this->createForm(NewsletterType::class, $newsletter);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$this->get('app.newsletter.manager')->uploadImage($newsletter);
			$newsletterContent = $newsletter->getContent();
			$newsletterName = $newsletter->getName();
			$fileContent = $this->get('app.newsletter.manager')->saveContentInTwig($newsletterContent, $newsletterName, $newsletter->getTheme());
			$newsletter->setFile($fileContent);
			$newsletter->setCreateAt(new \DateTime('now'));
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($newsletter);
			$entityManager->flush();
			$this->addFlash('success', "the newsletter" .$newsletter->getName()."has been sent");
			return $this->redirectToRoute('staff_newsletter_list');
		}
		return $this->render('staff/newsletter_create.html.twig', array(
			"form" => $form->createView()
		));

	}

	public function newsletterListAction()
	{
		$entityManager = $this->getDoctrine()->getManager();
		$newsletter = $entityManager->getRepository('AppBundle:Newsletter')->findAll();

		return $this->render('staff/newsletter_list.html.twig', array('newsletters' => $newsletter));
	}


	public function newsletterPreviewAction(Newsletter $newsletter)
	{
		return $this->render($newsletter->getFile(), array(
			"title" => $newsletter->getTitle()
		));
	}


	public function newsletterSendAction(Newsletter $newsletter)
	{
		$this->get('app.newsletter.manager')->sendNewsletter($newsletter);
		return $this->redirectToRoute('staff_newsletter_list');
	}
	
    public function offersActiveAction(Offer $offer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if($offer->getIsActive()) {
            $offer->setIsActive(false);
        }else{
            $offer->setIsActive(true);
        }
        $entityManager->persist($offer);
        $entityManager->flush();
        return $this->redirectToRoute('staff_offers_list');
    }

    public function offersModifyAction(Request $request, Offer $offer)
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();
            $this->addFlash('success', "The offer ".$offer->getName()." has been modified");
            return $this->redirectToRoute('staff_offers_list');
        }
        return $this->render('staff/offers_modify.html.twig', array(
            "form" => $form->createView()
        ));
    }

}
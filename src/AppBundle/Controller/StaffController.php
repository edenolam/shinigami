<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Card;
use AppBundle\Form\CustomerType;
use AppBundle\Form\CardType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\SearchCustomerType;

class StaffController extends Controller
{
	public function panelAction(Request $request)
	{

		$form = $this->createForm(SearchCustomerType::class);

		$form->handleRequest($request);

		$entity_manager = $this->getDoctrine()->getManager();
		if ($request->isMethod('POST')){
			if ($form->isSubmitted() && $form->isValid()){
				$customerData = $form->getData();
				$customer = $entity_manager->getRepository('AppBundle:Customer')
					->findCustomerWithoutCard(
						$customerData["firstname"],
						$customerData["lastname"],
						$customerData['phone']
					);
			}else{
				$cardData = $request->get('search_field');
				$card = $entity_manager->getRepository('AppBundle:Card')
					->findValidCardByNumber($cardData);
				return $this->redirectToRoute('staff_card', [
					'number' => $card->getNumber()
				]);
			}
		}

		return $this->render('staff/panel.html.twig', array(
			'form' =>$form->createView()
		));
	}

	public function manageCardAction(Card $card)
	{
		$customer = $card->getCustomer();
		return $this->render('staff/card.html.twig',[
			'customer' => $customer,
			'card'     => $card,
			'offers'   => NULL
		]);
	}

	public function editCustomerAction(Request $request, Card $card)
	{
		$form = $this->createForm(CustomerType::class, $card->getCustomer());
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			$em = $this->getDoctrine()->getManager();
			$em->persist($card->getCustomer());
			$em->flush();

			$this->addFlash('success', "The profile were modified.");

			return $this->redirectToRoute('staff_card', array('number' => $card->getNumber()));
		}

		return $this->render('staff/edit_customer.html.twig', array(
			"form" => $form->createView()
		));
	}

	public function editCardAction(Request $request, Card $card)
	{
		$form = $this->createForm(CardType::class, $card);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()){
			$em = $this->getDoctrine()->getManager();
			$em->persist($card);
			$em->flush();

			$this->addFlash('success', "The card were modified.");

			return $this->redirectToRoute('staff_card', array('number' => $card->getNumber()));
		}

		return $this->render('staff/edit_card.html.twig', array(
			"form" => $form->createView()
		));
	}
}
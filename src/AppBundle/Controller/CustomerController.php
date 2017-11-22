<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 15/11/2017
 * Time: 16:59
 */

namespace AppBundle\Controller;


use AppBundle\Form\CustomerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends Controller
{
    public function panelAction(Request $request)
    {
        $customer = $this->getUser()->getCustomer();
        $card = $customer->getCard();

        if($request->isMethod("POST")){
            $cardNumber = $request->get("number");
            $this->get('app.card.manager')->addCardToCustomer($cardNumber, $customer);
        }

        return $this->render('customer/panel.html.twig', array(
            "customer" => $customer,
            "card" => $card,
            "offers" => null,
			'gameSessions' => null
        ));
    }

    public function modifyAction(Request $request)
    {
        $form = $this->createForm(CustomerType::class, $this->getUser()->getCustomer());
        $form->handleRequest($request);

		//exit(dump($this->getUser()->getCustomer()));
        if($form->isSubmitted() && $form->isValid()){
			$birthday = $request->request->get('appbundle_account')['customer']['birthday'];
			$anniv = new \DateTime($birthday);
			$this->getUser()->getCustomer()->setBirthday($anniv);
            $em = $this->getDoctrine()->getManager();
            $em->persist($this->getUser()->getCustomer());
            $em->flush();

            $this->addFlash('success', "Your informations were modified.");

            return $this->redirectToRoute('customer_panel');
        }

        return $this->render('customer/modify.html.twig', array(
            "form" => $form->createView()
        ));
    }
}
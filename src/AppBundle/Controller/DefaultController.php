<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Account;
use AppBundle\Form\AccountType;
use AppBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends Controller
{
    public function indexAction(Request $request, AuthenticationUtils $authUtils, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        // Registration modal
        $frontService = $this->get('app.front.service');
        $user = $frontService->newAccount();
        $formRegister = $frontService->getCustomerRegisterForm($user);
        $formRegister->handleRequest($request);
        if ($formRegister->isSubmitted()) {
            if ($formRegister->isValid()) {
                $frontService->customerRegistration($request,$user);
                $this->addFlash("success", "All right ! You've been registered !");
                return $this->redirectToRoute('login');
            }else{
                $this->addFlash("error", "The informations you entered were not valid.");
                return $this->redirectToRoute('register');
            }
        }

        // Login modal
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        // Contact div
		$contact = $frontService->newContact();
		$formContact = $frontService->getContactForm($contact);
		$formContact->handleRequest($request);
		if ($formContact->isSubmitted() && $formContact->isValid()){
			$frontService->contact($contact);
            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/index.html.twig', [
            //'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'last_username' => $lastUsername,
            'error'         => $error,
            'formRegister' => $formRegister->createView(),
            'formContact' => $formContact->createView(),

        ]);
    }

}

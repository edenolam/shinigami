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
        $user = new Account();
        $formRegister = $this->createForm(AccountType::class, $user);

        $formRegister->handleRequest($request);

        if ($formRegister->isSubmitted()) {
            if ($formRegister->isValid()) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $user->setIsActive(true);
                $user->setRoles(array('ROLE_CUSTOMER'));

                $birthday = $request->request->get('appbundle_account')['customer']['birthday'];
                $user->getCustomer()->setBirthday(new \DateTime($birthday));
                $user->getCustomer()->setAccount($user);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash("success", "All right ! You've been registered !");
                return $this->redirectToRoute('login');

            }else{
                $this->addFlash("error", "The informations you entered were not valid.");
                return $this->redirectToRoute('register');
            }
        }

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();
        // replace this example code with whatever you need

		$contact = new Contact();
		$formContact = $this->createForm(ContactType::class, $contact);
		$formContact->handleRequest($request);

		if ($formContact->isSubmitted()){

			$message = (new \Swift_Message($contact->getSubject()))
				->setFrom($contact->getEmail())
				->setTo('contact@shinigami.com')
				->setBody( $this->renderView('emails/contact.html.twig',array(
					"contact" => $contact
				)),'text/html');

			if($mailer->send($message)){
                $this->addFlash('success', "Your contact email has been sent.");
            }else{
                $this->addFlash('error', "Your contact email has not been sent.");
            }

        }

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'last_username' => $lastUsername,
            'error'         => $error,
            'formRegister' => $formRegister->createView(),
            'formContact' => $formContact->createView(),

        ]);
    }

}

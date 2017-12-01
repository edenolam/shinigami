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
    public function indexAction(Request $request, AuthenticationUtils $authUtils, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new Account();
        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $user->setIsActive(true);
                $user->setRoles(array('ROLE_CUSTOMER'));

                $birthday = $request->request->get('appbundle_account')['customer']['birthday'];
                $user->getCustomer()->setBirthday(new \DateTime($birthday));

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash("success", "All right ! You've been registered !");
                return $this->redirectToRoute('login');

            }else{
                $this->addFlash("error", "The informations you entered were not valid.");
            }
        }

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();
        // replace this example code with whatever you need


        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'last_username' => $lastUsername,
            'error'         => $error,
            'form' => $form->createView()
        ]);
    }

    public function contactAction(Request $request, \Swift_Mailer $mailer)
	{
		$contact = new Contact();
		$form = $this->createForm(ContactType::class, $contact);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()){
			$message = (new \Swift_Message($contact->getSubject()))
				->setFrom($contact->getEmail())
				->setTo('contact@shinigami.com')
				->setBody( $this->renderView('partials/contact.html.twig',array(
					'name' => $contact->getName(),
					'message' => $contact->getMessage()
				)),'text/html');

			$mailer->send($message);
		}


		// or, you can also fetch the mailer service this way
		// $this->get('mailer')->send($message);

		return $this->render("partials/contact.html.twig");

	}



}

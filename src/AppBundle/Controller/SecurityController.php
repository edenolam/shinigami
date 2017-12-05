<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 14/11/2017
 * Time: 15:15
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Account;
use AppBundle\Form\AccountType;
use AppBundle\Form\AccountStaffType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{

    /**
     * Registration of a customer
     *
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
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
        return $this->render(
            'security/register.html.twig', array(
                'formRegister' => $formRegister->createView()
            )
        );
    }

    /**
     * Login
     *
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
	{
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

		return $this->render("security/login.html.twig", array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
	}

    /**
     * Registration of a staff member
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
	public function registerStaffAction(Request $request)
	{
        $frontService = $this->get('app.front.service');
        $user = $frontService->newAccount();
        $form = $frontService->getStaffRegisterForm($user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $frontService->customerRegistration($request,$user);
				$this->addFlash("success", "All right ! You've been registered !");
				return $this->redirectToRoute('login');
			}else{
				$this->addFlash("error", "The informations you entered were not valid.");
                return $this->redirectToRoute('register_staff');
			}
		}
		return $this->render(
			'security/registerStaff.html.twig', array(
				'form' => $form->createView()
			)
		);
	}

}
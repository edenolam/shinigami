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
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
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

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash("success", "All right ! You've been registered !");

            }else{
                $this->addFlash("error", "The informations you entered were not valid.");
            }
        }

        return $this->render(
            'security/register.html.twig', array(
                'form' => $form->createView()
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

}
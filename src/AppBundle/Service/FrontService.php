<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 05/12/2017
 * Time: 10:35
 */

namespace AppBundle\Service;


use AppBundle\Entity\Account;
use AppBundle\Entity\Contact;
use AppBundle\Form\AccountStaffType;
use AppBundle\Form\AccountType;
use AppBundle\Form\ContactType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Template;

class FrontService
{

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var ObjectManager
     */
    private $entityManager;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Template
     */
    private $template;

    public function __construct(FormFactoryInterface $formFactory, UserPasswordEncoderInterface $passwordEncoder, ObjectManager $entityManager, SessionInterface $session, RouterInterface $router, \Swift_Mailer $mailer, \Twig_Environment $template)
    {
        $this->formFactory = $formFactory;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->template = $template;
    }

    /**
     * Creates a new Account object
     *
     * @return Account
     */
    public function newAccount()
    {
        return new Account();
    }

    /**
     * Creates a customer registration form
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCustomerRegisterForm(Account $user)
    {
        return  $this->formFactory->create(AccountType::class, $user);
    }

    /**
     * Creates a staff registration form
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getStaffRegisterForm(Account $user)
    {
        return  $this->formFactory->create(AccountStaffType::class, $user);
    }

    /**
     * Registration of a customer
     *
     * @param Request $request
     * @param Account $user
     */
    public function customerRegistration(Request $request, Account $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $user->setIsActive(true);
        $user->setRoles(array('ROLE_CUSTOMER'));

        $birthday = $request->request->get('appbundle_account')['customer']['birthday'];
        $user->getCustomer()->setBirthday(new \DateTime($birthday));
        $user->getCustomer()->setAccount($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Registration of a staff member
     *
     * @param Account $user
     */
    public function staffRegistration(Account $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $user->setIsActive(true);
        $user->setRoles(array('ROLE_STAFF'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * Creates a new Contact object
     *
     * @return Contact
     */
    public function newContact()
    {
        return new Contact();
    }

    /**
     * Creates a contact form
     *
     * @param Contact $contact
     * @return FormInterface
     */
    public function getContactForm(Contact $contact)
    {
        return $this->formFactory->create(ContactType::class, $contact);
    }


    /**
     * Send a contact email
     *
     * @param Contact $contact
     */
    public function contact(Contact $contact)
    {
        $message = (new \Swift_Message($contact->getSubject()))
            ->setFrom($contact->getEmail())
            ->setTo('contact@shinigami.com')
            ->setBody($this->template->render('emails/contact.html.twig', array("contact" => $contact), "text/html"));

        if($this->mailer->send($message)){
            $this->session->getFlashBag()->add('success', "Your contact email has been sent.");
        }else{
            $this->session->getFlashBag()->add('error', "Your contact email has not been sent.");
        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 22/11/2017
 * Time: 14:23
 */

namespace AppBundle\Service;

use AppBundle\Entity\Newsletter;
use AppBundle\Entity\Card;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Offer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Template;

class NewsletterManager
{
    /**
     * @var KernelInterface
     */
	private $kernel;

	/**
	 * @var \Swift_Mailer
	 */
	private $mailer;

	/**
	 * @var Template
	 */
	private $template;

    /**
     * @var SessionInterface
     */
	private $session;
    /**
     * @var ObjectManager
     */
    private $entityManager;

	/**
	 * @var ContainerInterface
	 */
	private $container;



	public function __construct(KernelInterface $kernel, \Swift_Mailer $mailer, \Twig_Environment $template, SessionInterface $session, ContainerInterface $container, ObjectManager $entityManager)
	{
		$this->kernel = $kernel;
		$this->mailer = $mailer;
		$this->template = $template;
		$this->session = $session;
		$this->container = $container;
		$this->entityManager = $entityManager;
	}


    /**
     * Creates a newsletter
     *
     * @param Newsletter $newsletter
     */
    public function createNewsletter(Newsletter $newsletter)
    {
        $newsletter->setCreateAt(new \DateTime('now'));
        $this->uploadImage($newsletter);
        $this->save($newsletter);
    }


    /**
     * Sends a newsletter to all customers registered in Shinigami Laser website
     *
     * @param $newsletter
     */
    public function sendNewsletter($newsletter)
	{
		$theme = $newsletter->getTheme();
		$style = file_get_contents($this->kernel->getRootDir().'/Resources/views/emails/styles/'.$theme.'.css');

		$message = (new \Swift_Message($newsletter->getTitle()))
			->setFrom('newsletter@shinigamilaser.com')
			->setTo($this->getAllCustomersEmails())
			->setBody(
				$this->template->render('emails/base_email.html.twig', array(
					"newsletter" => $newsletter,
					'style' => $style
				)),
				'text/html'
			);

		$send = $this->mailer->send($message);
		if($send){
			$this->session->getFlashBag()->add('success', "The email has been sent ! ");
		}else{
			$this->session->getFlashBag()->add('error', "The email has not been sent ! :( ");
		}

	}
	/**
	 * Gives the emails of all the customers registered in Shinigami Laser website
	 *
	 * @return array
	 */
	private function getAllCustomersEmails()
	{
		$accounts = $this->entityManager->getRepository("AppBundle:Account")->findCustomerAccounts();
		$emails = array();
		foreach($accounts as $account){
			$emails[$account->getEmail()] = $account->getCustomer()->getNickname();
		}
		return $emails;
	}


    /**
     * Save a newsletter
     *
     * @param Newsletter $newsletter
     */
	private function save(Newsletter $newsletter)
	{
		$this->entityManager->persist($newsletter);
		$this->entityManager->flush();
		$this->session->getFlashBag()->add('success', "the newsletter " .$newsletter->getName()." has been saved");
	}

    /**
     * Uploads an image for a newsletter
     *
     * @param Newsletter $newsletter
     */
	public function uploadImage(Newsletter $newsletter)
	{
		if(null == $newsletter->getImage())
		{
			return;
		}
		$file = $newsletter->getImage();
		$fileName = md5(uniqid()).'.'.$file->guessExtension();
		$file->move( $this->container->getParameter('images_directory'), $fileName );
		$newsletter->setImage($fileName);
	}


    /**
     * Sends an email to a customer about a new offer
     *
     * @param Customer $customer
     * @param Card $card
     * @param Offer $offer
     */
    public function sendNewOfferEmail(Customer $customer, Card $card, Offer $offer)
    {
        $theme = "red";
        $style = file_get_contents($this->kernel->getRootDir().'/Resources/views/emails/styles/'.$theme.'.css');

        $message = (new \Swift_Message("You received a new offer !"))
            ->setFrom('noreply@shinigamilaser.com')
            ->setTo($customer->getAccount()->getEmail())
            ->setBody(
                $this->template->render('emails/offer_notification.html.twig', array(
                    "customer" => $customer,
                    "card" => $card,
                    "offer" => $offer,
                    'style' => $style
                )),
                'text/html'
            );

        $this->mailer->send($message);

    }
}
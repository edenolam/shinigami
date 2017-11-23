<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 22/11/2017
 * Time: 14:23
 */

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Template;
use AppBundle\Entity\Newsletter;

class NewsletterManager
{
	/**
	 * @var Kernel
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
	 * @var Session
	 */
	private $session;

	/**
	 * @var ContainerInterface
	 */
	private $container;


	public function __construct(KernelInterface $kernel, \Swift_Mailer $mailer, \Twig_Environment $template, Session $session, ContainerInterface $container)
{
	$this->kernel = $kernel;
	$this->mailer = $mailer;
	$this->template = $template;
	$this->session = $session;
	$this->container = $container;
}


	public function saveContentInTwig($content, $name, $theme)
	{
		$rootDir = $this->kernel->getRootDir();
		$path = "emails/templates/";
		$name = strtolower($name);
		$name = str_replace(" ", "_", $name);
		$filename = $name.".html.twig";

		$debut = "{% extends 'emails/base_email.html.twig' %}".PHP_EOL;

		$style = file_get_contents($rootDir.'/Resources/views/emails/styles/'.$theme.'.css');
		$style = "{% block stylesheet %}".$style."{% endblock %}".PHP_EOL.'{% block content %}'.PHP_EOL;

		$end = PHP_EOL."{% endblock %}";
		$file = fopen($rootDir.'/Resources/views/'.$path.$filename, 'w');
		fwrite($file, $debut.$style.$content.$end);
		fclose($file);

		return $path.$filename;
	}

	public function sendNewsletter(Newsletter $newsletter)
	{
		$message = (new \Swift_Message($newsletter->getTitle()))
			->setFrom('newsletter@shinigamilaser.com')
			->setTo('julienbasquin@hotmail.fr')
			->setBody(
				$this->template->render($newsletter->getFile(), array('title' => $newsletter->getTitle())),
				'text/html'
			);

		$send = $this->mailer->send($message);
		if($send){

			$this->session->getFlashBag()->add('success', "The email has been sent ! ");
		}else{
			$this->session->getFlashBag()->add('error', "The email has not been sent ! :( ");
		}

	}

	public function uploadImage(Newsletter $newsletter)
	{
		$file = $newsletter->getImage();
		$fileName = md5(uniqid()).'.'.$file->guessExtension();
		$file->move( $this->container->getParameter('images_directory'), $fileName );
		$newsletter->setImage($fileName);
	}
}
<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 23/11/2017
 * Time: 12:57
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Newsletter;
use AppBundle\Form\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StaffNewsletterController extends Controller
{
    /**
     * List of the newsletters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->getRepository("AppBundle:Newsletter")->getAllNewsletterQuery();

        $paginator  = $this->get('knp_paginator');
        $newsletters = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render('staff/newsletter/newsletter_list.html.twig', array('newsletters' => $newsletters));
    }

    /**
     * Creation of a newsletter
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->get('app.newsletter.manager')->createNewsletter($newsletter);
            return $this->redirectToRoute('staff_newsletter_list');
        }
        return $this->render('staff/newsletter/newsletter_create.html.twig', array(
            "form" => $form->createView()
        ));

    }

    /**
     * Edition of a newsletter
     *
     * @param Request $request
     * @param Newsletter $newsletter
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function modifyAction(Request $request, Newsletter $newsletter)
    {
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->get('app.newsletter.manager')->createNewsletter($newsletter);
            return $this->redirectToRoute('staff_newsletter_list');
        }
        return $this->render('staff/newsletter/newsletter_modify.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * Preview of a newsletter
     *
     * @param Newsletter $newsletter
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function previewAction(Newsletter $newsletter)
    {
    	$theme = $newsletter->getTheme();
		$style = file_get_contents($this->get('kernel')->getRootDir().'/Resources/views/emails/styles/'.$theme.'.css');
        return $this->render('emails/base_email.html.twig', array(
            "newsletter" => $newsletter,
			'style' => $style
        ));
    }

    /**
     * Sending of a newsletter
     *
     * @param Newsletter $newsletter
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function sendAction(Newsletter $newsletter)
    {
        $this->get('app.newsletter.manager')->sendNewsletter($newsletter);
        return $this->redirectToRoute('staff_newsletter_list');
    }

}
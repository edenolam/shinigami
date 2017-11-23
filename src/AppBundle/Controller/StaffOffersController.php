<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 23/11/2017
 * Time: 13:01
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Offer;
use AppBundle\Form\OfferType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StaffOffersController extends Controller
{
    /**
     * List of offers
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $offers = $entityManager->getRepository("AppBundle:Offer")->findAll();
        return $this->render('staff/offers/offers_list.html.twig', array('offers' => $offers));
    }

    /**
     * Creation of an offer
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $offer->setIsActive(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();
            $this->addFlash('success', "The offer ".$offer->getName()." has been saved");
            return $this->redirectToRoute('staff_offers_list');
        }
        return $this->render('staff/offers/offers_create.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * Activation/Desactivation of an offer
     *
     * @param Offer $offer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activeAction(Offer $offer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if($offer->getIsActive()) {
            $offer->setIsActive(false);
        }else{
            $offer->setIsActive(true);
        }
        $entityManager->persist($offer);
        $entityManager->flush();
        return $this->redirectToRoute('staff_offers_list');
    }

    /**
     * Edit of an offer
     *
     * @param Request $request
     * @param Offer $offer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function modifyAction(Request $request, Offer $offer)
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();
            $this->addFlash('success', "The offer ".$offer->getName()." has been modified");
            return $this->redirectToRoute('staff_offers_list');
        }
        return $this->render('staff/offers/offers_modify.html.twig', array(
            "form" => $form->createView()
        ));
    }
}
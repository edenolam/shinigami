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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StaffOffersController extends Controller
{
    /**
     * List of offers
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->getRepository("AppBundle:Offer")->getAllOffersQuery();

        $paginator  = $this->get('knp_paginator');
        $offers = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

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
    public function activeAjaxAction(Offer $offer)
    {
        $response = new JsonResponse();
        $entityManager = $this->getDoctrine()->getManager();

        if($offer->getIsActive()) {
            $offer->setIsActive(false);
            $response->setData(array("result" => false));
        }else{
            $offer->setIsActive(true);
            $response->setData(array("result" => true));
        }
        $entityManager->persist($offer);
        $entityManager->flush();

        return $response;
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
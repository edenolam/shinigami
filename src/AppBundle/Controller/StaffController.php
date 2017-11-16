<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 15/11/2017
<<<<<<< HEAD
 * Time: 16:59
=======
 * Time: 16:56
>>>>>>> commit sans push
 */

namespace AppBundle\Controller;

<<<<<<< HEAD

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StaffController extends Controller
{

    public function panelAction()
    {

    }

=======
use AppBundle\Entity\Account;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class staffController extends Controller
{
	public function indexAction()
	{
		$entity_manager = $this->getDoctrine()->getManager();

		$accounts = $entity_manager->getRepository('AppBundle:Account');

		return $this->render('staff/index.html.twig', array(
			'accounts' => $accounts,
		));
	}
>>>>>>> commit sans push
}
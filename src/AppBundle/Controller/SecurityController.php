<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 14/11/2017
 * Time: 15:15
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{

    public function registerAction()
    {
        return $this->render("security/register.html.twig");
    }

    public function loginAction()
	{
		return $this->render("security/login.html.twig");
	}

}
<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 15/11/2017
 * Time: 10:30
 */

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;

class SecurityContext implements Context, KernelAwareContext
{

	use KernelDictionary;
	/**
	 * @Then /^I should be logged as "([^"]*)"$/
	 */
	public function iShouldBeLoggedAs($username)
	{
		$token = $this->getContainer()->get("security.token_storage")->getToken();
		dump($token);
		if($token->getUser()->getUsername() != $username){
			throw new Exception("Your are not $username !!!");
		}
	}


	/**
	 * Sets Kernel instance.
	 *
	 * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
	 */
	public function setKernel(\Symfony\Component\HttpKernel\KernelInterface $kernel)
	{
		$this->kernel = $kernel;
	}
}
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

class SecurityContext implements KernelAwareContext
{
	use KernelDictionary;
	/**
	 * @Then /^I should be logged as "([^"]*)"$/
	 */
	public function iShouldBeLoggedAs($username)
	{
		$session = $this->dictionary->getContainer()->get('session');
		exit(dump($session));
	}


	/**
	 * Sets Kernel instance.
	 *
	 * @param \Symfony\Component\HttpKernel\KernelInterface $kernel
	 */
	public function setKernel(\Symfony\Component\HttpKernel\KernelInterface $kernel)
	{
		// TODO: Implement setKernel() method.
	}
}
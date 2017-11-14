<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 14/11/2017
 * Time: 14:55
 */

use Behat\Behat\Context\Context;

class HomepageContext implements Context
{
    /**
     * @Then /^I should see the button "([^"]*)"$/
     *
     * Find a button with the name $arg1
     */
    public function iShouldSeeTheButton($arg1)
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }
}
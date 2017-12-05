<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Tests\AppBundle\Mink\CoreMink;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * @BeforeStep
     */
    public function beforeStep()
    {
        $this->getSession()->resizeWindow(1980, 1080, 'current');
    }

    /**
     * @Then /^I should see the "([^"]*)" modal$/
     */
    public function iShouldSeeTheModal($modal)
    {
        $this->assertElementOnPage($modal);
    }

    /**
     * @Given /^I click on "([^"]*)"$/
     */
    public function iClickOn($id)
    {
        $element = $this->getSession()->getPage()->findById($id);
        $element->click();
    }



    /**
     * @Given /^I set in "([^"]*)" with "([^"]*)"$/
     */
    public function iSetInWith($elementId, $value)
    {
        $element = $this->getSession()->getPage()->findById($elementId);
        $element->setValue($value);
    }

    /**
     * @When I scroll :selector into view
     *
     * @param string $selector Allowed selectors: #id, .className, //xpath
     *
     * @throws \Exception
     */
    public function scrollIntoView($selector)
    {
        $locator = substr($selector, 0, 1);

        switch ($locator) {
            case '/' : // XPath selector
                $function = <<<JS
(function(){
  var elem = document.evaluate($selector, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
  elem.scrollIntoView(false);
})()
JS;
                break;

            case '#' : // ID selector
                $selector = substr($selector, 1);
                $function = <<<JS
(function(){
  var elem = document.getElementById("$selector");
  elem.scrollIntoView(false);
})()
JS;
                break;

            case '.' : // Class selector
                $selector = substr($selector, 1);
                $function = <<<JS
(function(){
  var elem = document.getElementsByClassName("$selector");
  elem[0].scrollIntoView(false);
})()
JS;
                break;

            default:
                throw new \Exception(__METHOD__ . ' Couldn\'t find selector: ' . $selector . ' - Allowed selectors: #id, .className, //xpath');
                break;
        }

        try {
            $this->getSession()->executeScript($function);
        } catch (Exception $e) {
            throw new \Exception(__METHOD__ . ' failed');
        }
    }

    /**
     * @Given /^I pick the date "([^"]*)"$/
     */
    public function iPickTheDate($date)
    {
        $function = <<<JS
    (function(){
      $( ".datepicker" ).pickadate("picker").set('select', '$date', { format: 'dd/mm/yyyy' });
    })()
JS;
        try {
            $this->getSession()->executeScript($function);
        } catch (Exception $e) {
            throw new \Exception(__METHOD__ . ' failed');
        }
    }

    /**
     * @Given /^I wait "([^"]*)"$/
     */
    public function iWait($time)
    {
        $this->getSession()->wait($time);
    }


}

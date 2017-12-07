<?php

use AppBundle\DataFixtures\ORM\BehatFixtures;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\MinkContext;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * @Given /^Database init$/
     */
    public function databaseInit()
    {
        print "Drop and create dababase " . PHP_EOL;
        $command = "php bin/console doctrine:database:drop --force";
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $command = "php bin/console doctrine:database:create";
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $command = "php bin/console doctrine:schema:update --force";
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        echo $process->getOutput();

        print "Loading fixtures for test " . PHP_EOL;
        self::loadDataFixture('src/AppBundle/DataFixtures/ORM/BehatFixtures.php', "test");
    }

    /**
     * Load data fixtures by executing the console command
     *
     * @param $fixture The directory to load data fixtures from
     * @param $env The environment name
     */
    public static function loadDataFixture($fixture, $env)
    {
        print(__DIR__) . PHP_EOL;
        $command = "php bin/console doctrine:fixtures:load --env=".$env." --fixtures=".$fixture." -n";

        $process = new Process($command);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();
    }

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
     * @Given /^I pick the time "([^"]*)"$/
     */
    public function iPickTheTime($time)
    {
        $function = <<<JS
    (function(){
      $( ".timepicker" ).pickatime("picker").set('select', '$time', { format: 'hh:mm' });
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

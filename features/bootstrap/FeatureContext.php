<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
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
     * @Given /^I wait "([^"]*)"$/
     */
    public function iWait($time)
    {
        $this->getSession()->wait($time);
    }

    /**
     * @Given /^I scroll down$/
     */
    public function iScrollDown()
    {
        $this->getSession()->executeScript('window.scrollTo(0,100);');
    }

    /**
     * @Given /^I pick the date "([^"]*)" in "([^"]*)"$/
     */
    public function iPickTheDateIn($date, $class)
    {
        $function = <<<JS
    (function(){
      $( "." + '$class').pickadate("picker").set('select', '$date', { format: 'dd/mm/yyyy' });
    })()
JS;
        try {
            $this->getSession()->executeScript($function);
        } catch (Exception $e) {
            throw new \Exception(__METHOD__ . ' failed');
        }
    }


}

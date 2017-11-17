<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 17/11/2017
 * Time: 15:19
 */

class StaffContext extends \Behat\MinkExtension\Context\MinkContext implements \Behat\Behat\Context\Context
{
    private $driver;
    private $session;

    public function __construct()
    {
        $this->driver = new \Behat\Mink\Driver\Selenium2Driver('firefox');
        $this->session = new \Behat\Mink\Session($this->driver);
        $this->session->start();
    }
}
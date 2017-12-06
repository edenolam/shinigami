<?php
/**
 * Created by PhpStorm.
 * User: hello
 * Date: 06/12/2017
 * Time: 10:59
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Account;
use AppBundle\Entity\Center;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BehatFixtures implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;


    public function setContainer(ContainerInterface $container = null)
{
    $this->container = $container;
}

    public function load(ObjectManager $manager)
    {
        foreach ($this->getAccountData() as $accountData) {
            $account = new Account();
            $account->setUsername($accountData['username']);
            $account->setPassword($accountData['password']);
            $account->setEmail($accountData['email']);
            $account->setRoles(array($accountData['roles']));
            $account->setIsActive(true);
            $manager->persist($account);
        }

        foreach ($this->getCenterData() as $centerData) {
            $center = new Center();
            $center->setCode($centerData['code']);
            $center->setAdress($centerData['adress']);
            $center->setVisits(0);
            $manager->persist($center);
        }

        $manager->flush();
    }

    private function getAccountData()
    {
        return [
            [
                'username' => 'staff',
                'password' => '$2y$13$Zi1mzLOnqeDHupSMFok8xeNgmPQqJj.b69qjxXzX9GBbtgnFvT2PS',
                'email' => 'staff@email.com',
                'roles' => 'ROLE_STAFF',
            ],
            [
                'username' => 'superstaff',
                'password' => '$2y$13$Zi1mzLOnqeDHupSMFok8xeNgmPQqJj.b69qjxXzX9GBbtgnFvT2PS',
                'email' => 'superstaff@email.com',
                'roles' => 'ROLE_SUPER_ADMIN',
            ],
        ];
    }

    private function getCenterData()
    {
        return array(
            array(
                "code" => "123",
                "adress" => "157 boulevard Macdonald, 75019 Paris"
            ),
            array(
                "code" => "967",
                "adress" => "2 rue du petit chemin, 68102 Bourg-Palette"
            ),
            array(
                "code" => "621",
                "adress" => "6 avenue des marchands, 54623 Celadopole"
            )
        );
    }
}
<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Account;
use AppBundle\Entity\Center;
use AppBundle\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		$staffAccount = new Account();
		$staffAccount->setUsername('staff');
		$staffAccount->setPassword('$2y$13$Zi1mzLOnqeDHupSMFok8xeNgmPQqJj.b69qjxXzX9GBbtgnFvT2PS');
		$staffAccount->setEmail('staff@shinigamilaser.com');
		$staffAccount->setRoles(["ROLE_STAFF"]);
		$staffAccount->setIsActive(TRUE);

		$customerAccount = new Account();
		$customerAccount->setUsername("customer");
		$customerAccount->setPassword('$2y$13$U.2gX5OCxlTJFZcPydbr5e5GukbLHeCMgbTt4Bp6lDbzxBR4.7j4a');
		$customerAccount->setEmail("customer@customer.com");
		$customerAccount->setRoles(["ROLE_CUSTOMER"]);
		$customerAccount->setIsActive(TRUE);


		$customer = new Customer();
		$customer->setFirstname("Julien");
		$customer->setLastname("Basquin");
		$customer->setNickname("crazykiller");
		$customer->setAdress("70 rue de tocqueville");
		$customer->setPhone("0661916148");
		$customer->setBirthday(new \DateTime("04/02/1981"));


		$center = new Center();
		$center->setCode(123);
		$center->setAdress("paris 19");
		$center->setVisits(0);


		$customerAccount->setCustomer($customer);
		$manager->persist($center);
		$manager->persist($staffAccount);
		$manager->persist($customerAccount);
		$manager->flush();

	}


}
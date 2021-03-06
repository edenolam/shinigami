<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter your firstname.")
     *
     * @ORM\Column(name="firstname", type="string", length=255)
	 *
	 * @Assert\Regex(
	 *     pattern="/\d/",
	 *     match=false,
	 *     message="Your firstname cannot contain a number"
	 * )
	 */
    private $firstname;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter your lastname.")
     *
     * @ORM\Column(name="lastname", type="string", length=255)
	 * @Assert\Regex(
	 * pattern="/\d/",
	 * match=false,
	 * message="Your lastname cannot contain a number"
	 * )
     */
    private $lastname;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter a nickname.")
	 * @Assert\Length(min = 2, max = 15, minMessage = "2 caracteres minimum", maxMessage = "15 caracteres maximum")
     *
     * @ORM\Column(name="nickname", type="string", length=255, unique=true)
     */
    private $nickname;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter your adress.")
	 * @Assert\Valid
     * @ORM\Column(name="adress", type="string", length=255)
     */
    private $adress;

	/**
	 * @var string
	 * @Assert\NotBlank(message="Please enter your city.")
	 * @Assert\Valid
	 * @ORM\Column(name="city", type="string", length=255)
	 */
    private $city;

    /**
     * @var string
     * @Assert\NotBlank(message="Please enter your phone.")
	 * @Assert\Length(min = 10, max = 10, minMessage = "10 numbers minimum", maxMessage = "10 numbers maximum")
	 * @Assert\Regex(pattern="/^[0-9]*$/", message="number_only")
     *
     * @ORM\Column(name="phone", type="string", length=255, unique=true)
     */
    private $phone;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="Please enter your birthday.")
     *
     * @ORM\Column(name="birthday", type="datetimetz")
     */
    private $birthday;

    /**
     * One Customer has One Card.
     * @ORM\OneToOne(targetEntity="Card", mappedBy="customer")
     */
    private $card;

    /**
     * One Cart has One Customer.
     * @ORM\OneToOne(targetEntity="Account", inversedBy="customer", cascade={"persist"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    private $account;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Customer
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Customer
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return Customer
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set adress
     *
     * @param string $adress
     *
     * @return Customer
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Customer
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Customer
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return Customer
     */
    public function setCard(\AppBundle\Entity\Card $card = null)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return \AppBundle\Entity\Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Customer
     */
    public function setCity($city)
    {
        $this->city = $city;
    }
    
    /*
     * Set account
     *
     * @param \AppBundle\Entity\Account $account
     *
     * @return Customer
     */
    public function setAccount(\AppBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /*
     * Get account
     *
     * @return \AppBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}

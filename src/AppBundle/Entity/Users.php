<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(
 *     name="users",
 *     indexes={
 *         @ORM\Index(name="first_name_idx", columns={"firstName"}),
 *         @ORM\Index(name="last_name_idx", columns={"lastName"}),
 *         @ORM\Index(name="birthday_idx", columns={"birthday"}),
 *         @ORM\Index(name="email_idx", columns={"email"}),
 *         @ORM\Index(name="home_adderss_idx", columns={"homeAddress"}),
 *         @ORM\Index(name="home_zip_idx", columns={"homeZip"}),
 *         @ORM\Index(name="home_city_idx", columns={"homeCity"}),
 *         @ORM\Index(name="work_address_idx", columns={"workAddress"}),
 *         @ORM\Index(name="work_city_idx", columns={"workCity"}),
 *         @ORM\Index(name="sanitized_phone_idx", columns={"sanitizedPhone"}),
 *         @ORM\Index(name="company_name_idx", columns={"companyName"}),
 *         @ORM\Index(name="position_idx", columns={"position"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsersRepository")
 */
class Users
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
     *
     * @ORM\Column(name="firstName", type="string", length=100)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=100)
     */
    private $lastName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date")
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="homeCity", type="string", length=100)
     */
    private $homeCity;

    /**
     * @var string
     *
     * @ORM\Column(name="homeAddress", type="string", length=255)
     */
    private $homeAddress;

    /**
     * @var int
     *
     * @ORM\Column(name="homeZip", type="integer")
     */
    private $homeZip;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=50)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="sanitizedPhone", type="string", length=13)
     */
    private $sanitizedPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="companyName", type="string", length=100)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="workCity", type="string", length=100)
     */
    private $workCity;

    /**
     * @var string
     *
     * @ORM\Column(name="workAddress", type="string", length=255)
     */
    private $workAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=50)
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="cv", type="text")
     */
    private $cv;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Users
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Users
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set birthday
     *
     * @param \DateTime|string $birthday
     *
     * @return Users
     */
    public function setBirthday($birthday)
    {
        if (!($birthday instanceof \DateTime)) {
            $birthday = new \DateTime((string) $birthday);
        }
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
     * Get age
     *
     * @return int|null
     */
    public function getAge()
    {
        if (!$this->birthday) {
            return null;
        }

        $diff = (new \DateTime('now'))->diff($this->birthday);

        return $diff->y;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set homeCity
     *
     * @param string $homeCity
     *
     * @return Users
     */
    public function setHomeCity($homeCity)
    {
        $this->homeCity = $homeCity;

        return $this;
    }

    /**
     * Get homeCity
     *
     * @return string
     */
    public function getHomeCity()
    {
        return $this->homeCity;
    }

    /**
     * Set homeAddress
     *
     * @param string $homeAddress
     *
     * @return Users
     */
    public function setHomeAddress($homeAddress)
    {
        $this->homeAddress = $homeAddress;

        return $this;
    }

    /**
     * Get homeAddress
     *
     * @return string
     */
    public function getHomeAddress()
    {
        return $this->homeAddress;
    }

    /**
     * Set homeZip
     *
     * @param integer $homeZip
     *
     * @return Users
     */
    public function setHomeZip($homeZip)
    {
        $this->homeZip = $homeZip;

        return $this;
    }

    /**
     * Get homeZip
     *
     * @return int
     */
    public function getHomeZip()
    {
        return $this->homeZip;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Users
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        $this->sanitizedPhone = $this->sanitizePhone($this->phone);

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
     * Get normalizedPhone
     *
     * @return string
     */
    public function getSanitizedPhone()
    {
        return $this->sanitizedPhone;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Users
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set workCity
     *
     * @param string $workCity
     *
     * @return Users
     */
    public function setWorkCity($workCity)
    {
        $this->workCity = $workCity;

        return $this;
    }

    /**
     * Get workCity
     *
     * @return string
     */
    public function getWorkCity()
    {
        return $this->workCity;
    }

    /**
     * Set workAddress
     *
     * @param string $workAddress
     *
     * @return Users
     */
    public function setWorkAddress($workAddress)
    {
        $this->workAddress = $workAddress;

        return $this;
    }

    /**
     * Get workAddress
     *
     * @return string
     */
    public function getWorkAddress()
    {
        return $this->workAddress;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return Users
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set cv
     *
     * @param string $cv
     *
     * @return Users
     */
    public function setCv($cv)
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * Get cv
     *
     * @return string
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * @param string $phone
     * @return string
     */
    protected function sanitizePhone($phone)
    {
        return preg_replace('/[^\d]/', '', $phone);
    }
}


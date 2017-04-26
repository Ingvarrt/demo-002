<?php

namespace AppBundle\Service\Importer\Writer;

use AppBundle\Entity\Users;
use Doctrine\ORM\EntityManager;

class UsersWriter implements WriterInterface
{
    /** @var EntityManager */
    protected $em;

    /**
     * @param EntityManager $em
     * @return $this
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        return $this;
    }

    public function prepare()
    {
        return;
    }

    /**
     * @param $data
     * @return bool
     */
    public function process($data)
    {
        if (!is_array($data) || empty($data)) {
            throw new WriterException('Data is empty');
        }

        $user = $this->createUserFromArray($data);

        $this->em->persist($user);
        $this->em->flush();
        $this->em->clear();

        return true;
    }

    /**
     * @return void
     */
    public function finish()
    {
        return;
    }

    /**
     * @param array $data
     * @return Users
     */
    protected function createUserFromArray(array $data)
    {
        $user = new Users();

        $user->setFirstName($data[0]);
        $user->setLastName($data[1]);
        $user->setBirthday($data[2]);
        $user->setEmail($data[3]);
        $user->setHomeCity($data[4]);
        $user->setHomeAddress($data[5]);
        $user->setHomeZip($data[6]);
        $user->setPhone($data[7]);
        $user->setCompanyName($data[8]);
        $user->setWorkCity($data[9]);
        $user->setWorkAddress($data[10]);
        $user->setPosition($data[11]);
        $user->setCv($data[12]);

        return $user;
    }
}
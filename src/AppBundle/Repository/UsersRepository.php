<?php

namespace AppBundle\Repository;

class UsersRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param array $filter Possible keys: firstName, lastName, birthday, age, email, homeAddress, companyAddress,
     *                                     phone, companyName, jobPosition, cv
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getSearchQB(array $filter = [])
    {
        $qb = $this->createQueryBuilder('u');

        foreach ($filter as $name => $value) {
            switch ($name) {
                case 'firstName':
                    $qb->andWhere('u.firstName LIKE :firstName')
                        ->setParameter('firstName', $value . '%');
                    break;

                case 'lastName':
                    $qb->andWhere('u.lastName LIKE :lastName')
                        ->setParameter('lastName', $value . '%');
                    break;

                case 'birthday':
                    $qb->andWhere('u.birthday LIKE :birthday')
                        ->setParameter('birthday', $value);
                    break;

                case 'age':
                    $ageStmt = 'TIMESTAMPDIFF(YEAR, u.birthday, CURRENT_DATE())';
                    $range = 3;
                    $qb->andWhere($ageStmt . ' > :age - ' . $range)
                        ->andWhere($ageStmt . ' < :age + ' . $range)
                        ->setParameter('age', $value);
                    break;

                case 'email':
                    $qb->andWhere('u.email LIKE :email')
                        ->setParameter('email', $value . '%');
                    break;

                case 'homeAddress':
                    $qb->andWhere($qb->expr()->orX(
                        $qb->expr()->andX('u.homeAddress LIKE :addr'),
                        $qb->expr()->andX('u.homeZip LIKE :addr'),
                        $qb->expr()->andX('u.homeCity LIKE :addr')
                    ))
                        ->setParameter('addr', $value . '%');
                    break;

                case 'companyAddress':
                    $qb->andWhere($qb->expr()->orX(
                        $qb->expr()->andX('u.workAddress LIKE :addr'),
                        $qb->expr()->andX('u.workCity LIKE :addr')
                    ))
                        ->setParameter('addr', $value . '%');
                    break;

                case 'phone':
                    $qb->andWhere('u.sanitizedPhone LIKE :phone')
                        ->setParameter('phone', $value . '%');
                    break;

                case 'companyName':
                    $qb->andWhere('u.companyName LIKE :companyName')
                        ->setParameter('companyName', $value . '%');
                    break;

                case 'jobPosition':
                    $qb->andWhere('u.position LIKE :position')
                        ->setParameter('position', $value . '%');
                    break;

                case 'cv':
                    $qb->andWhere('u.cv LIKE :cv')
                        ->setParameter('cv', '%' . $value . '%');
                    break;
            }
        }

        return $qb;
    }

    /**
     * @param array $filter
     * @return int
     */
    public function getSearchResultCount(array $filter = [])
    {
        $qb = $this->getSearchQB($filter);
        $qb->select('count(u.id)');

        return $qb->getQuery()
            ->getSingleScalarResult();
    }
}

<?php

namespace Fulgurio\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 */
class UserRepository extends EntityRepository
{
    /**
     * Class name of the returned entities
     * @var string
     */
    protected $userEntityName = 'FulgurioUserBundle:User';

    /**
     * Number of users per page
     *
     * @var number
     */
    const NB_PER_PAGE = 10;

    /**
     * Get users with pagination
     *
     * @param Paginator $paginator KNPPaginator
     * @param number $page Current page
     * @param string $filter
     */
    public function findAllWithPaginator($paginator, $page, $filter)
    {
        if (!is_null($filter) && trim($filter) != '')
        {
            $query = $this->getEntityManager()->createQuery('SELECT u FROM ' . $this->userEntityName .' u WHERE u.username LIKE :username OR u.email LIKE :email ORDER BY u.username ASC, u.email ASC');
            $query->setParameter('username', $filter . '%');
            $query->setParameter('email', $filter . '%');
        }
        else
        {
            $query = $this->getEntityManager()->createQuery('SELECT u FROM ' . $this->userEntityName .' u ORDER BY u.username ASC, u.email ASC');
        }
        return ($paginator->paginate($query, $page, self::NB_PER_PAGE));
    }
}

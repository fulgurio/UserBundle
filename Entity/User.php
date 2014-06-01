<?php
namespace Fulgurio\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of User
 *
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 *
 *         @ORM\Entity(repositoryClass="Fulgurio\UserBundle\Repository\UserRepository")
 *         @ORM\Table(name="fulgurio_user")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
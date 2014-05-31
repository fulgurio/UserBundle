<?php
/*
 * This file is part of the FulgurioUserBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\UserBundle\Tests\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Fulgurio\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('user1');
        $user1->setPlainPassword('user1');
        $user1->setEmail('user1@example.com');
        $user1->setEnabled(TRUE);
        $manager->persist($user1);
        $manager->flush();

        $user2 = new User();
        $user2->setUsername('user2');
        $user2->setPlainPassword('user2');
        $user2->setEmail('user2@example.com');
        $user2->setEnabled(TRUE);
        $manager->persist($user2);
        $manager->flush();
    }
}

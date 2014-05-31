<?php
/*
 * This file is part of the FulgurioUserBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\UserBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    /**
     * Test setup
     */
    public function setUp()
    {
        // add all your doctrine fixtures classes
        $classes = array(
            'Fulgurio\UserBundle\Tests\DataFixtures\ORM\LoadUserData'
        );
        $this->loadFixtures($classes);
    }

    /**
     * Dump html result for debug
     *
     * @param type $client
     */
    public function printResult($client)
    {
        print_r($client->getResponse()->getContent());
        exit;
    }
}

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

use Fulgurio\UserBundle\Tests\Controller\WebTestCase;

class UserManagerControllerTest extends WebTestCase
{
    /**
     * Test of index page
     */
    public function testIndex()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $crawler = $client->request('GET', '/admin');
        $this->assertCount(1, $crawler->filter('a:contains("users_manager")'));
    }

    /**
     * Test users listing
     */
    public function testListAction()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $crawler = $client->request('GET', '/admin/users');
//        $this->printResult($client);
        $this->assertCount(2, $crawler->filter('tbody tr'));
    }

    /**
     * Test user removing
     */
    public function testBanAction()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $client->followRedirects();
        $crawler = $client->request('GET', '/admin/users');
        $this->assertCount(2, $crawler->filter('a[title="ban"]'));

        $banLink = $crawler->filter('a[title="ban"]')->link();
        $crawler = $client->click($banLink);
        $form = $crawler->filter('button:contains("form.confirmation.yes")')->form();
        $crawler = $client->submit($form);
        $this->assertCount(1, $crawler->filter('a[title="ban"]'));

        $unbanLink = $crawler->filter('a[title="unban"]')->link();
        $crawler = $client->click($unbanLink);
        $form = $crawler->filter('button:contains("form.confirmation.yes")')->form();
        $crawler = $client->submit($form);
        $this->assertCount(2, $crawler->filter('a[title="ban"]'));
    }

    /**
     * Test user removing
     */
    public function testRemoveAction()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $client->followRedirects();
        $crawler = $client->request('GET', '/admin/users');
        $this->assertCount(2, $crawler->filter('tbody tr'));
        $deleteLink = $crawler->filter('a[title="delete"]')->link();
        $crawler = $client->click($deleteLink);

        $form = $crawler->filter('button:contains("form.confirmation.yes")')->form();
        $crawler = $client->submit($form);
        $this->assertCount(1, $crawler->filter('tbody tr'));
    }

    }

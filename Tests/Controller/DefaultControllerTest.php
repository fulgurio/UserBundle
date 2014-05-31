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

class DefaultControllerTest extends WebTestCase
{
    /**
     * Test setup
     */
    public function setUp()
    {
    }

    /**
     * Test of index page
     */
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertCount(1, $crawler->filter('a:contains("Login")'));
        $this->assertCount(1, $crawler->filter('a:contains("Register")'));
        $loginLink = $crawler->filter('a:contains("Login")')->link();
        $crawler = $client->click($loginLink);
        $this->assertCount(1, $crawler->filter('form[action="/login_check"]'));

        $registerLink = $crawler->filter('a[href="/register/"]')->link();
        $crawler = $client->click($registerLink);
        $this->assertCount(1, $crawler->filter('form[action="/register/"]'));
    }
}

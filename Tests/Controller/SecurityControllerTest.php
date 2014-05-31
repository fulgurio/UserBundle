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

class SecurityControllerTest extends WebTestCase
{
    /**
     * Test of empty login form
     */
    public function testEmptyLogin()
    {
        $data = array(
            'login[email]' => '',
            'login[password]' => ''
        );
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("Bad credentials")'));
    }

    /**
     * Test of unknow username in login form
     */
    public function testUnknowUsernameLogin()
    {
        $data = array(
            'login[email]' => 'unknowuser',
            'login[password]' => ''
        );
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("Bad credentials")'));
        $security = $client->getContainer()->get('security.context');
        $this->assertFalse($security->isGranted('ROLE_USER'));
    }

    /**
     * Test of existing username in login form
     */
    public function testExistingUsernameLogin()
    {
        $data = array(
            'login[email]' => 'user1',
            'login[password]' => 'user1'
        );
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);

        // Authentified
        $security = $client->getContainer()->get('security.context');
        $this->assertTrue($security->isGranted('ROLE_USER'));

        // We check all URLs that authentied user still not use
        $client->followRedirects(FALSE);
        $crawler = $client->request('GET', '/login');
        $this->assertTrue($client->getResponse()->isRedirect('/'));
        $crawler = $client->request('GET', '/register/');
        $this->assertTrue($client->getResponse()->isRedirect('/'));
    }

    /**
     * Test of unknow email in login form
     */
    public function testUnknowEmailLogin()
    {
        $data = array(
            'login[email]' => 'unknow@email.com',
            'login[password]' => ''
        );
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);
        // Display error
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("Bad credentials")'));
        // Not authentified
        $security = $client->getContainer()->get('security.context');
        $this->assertFalse($security->isGranted('ROLE_USER'));
    }

    /**
     * Test of existing email in login form
     */
    public function testExistingEmailLogin()
    {
        $data = array(
            'login[email]' => 'user1@example.com',
            'login[password]' => 'user1'
//            'login[remember_me]' => ''
        );
        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);

        // Authentified
        $security = $client->getContainer()->get('security.context');
        $this->assertTrue($security->isGranted('ROLE_USER'));
    }
}

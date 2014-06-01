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

class RegistrationControllerTest extends WebTestCase
{
    /**
     * Test of registering new user
     */
    public function testSubscribe()
    {
        $data = array(
            'registration[email]' => '',
            'registration[username]' => '',
            'registration[plainPassword][first]' => '',
            'registration[plainPassword][second]' => ''
        );
        $client = static::createClient();
        $crawler = $client->request('GET', '/register/');

        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("fos_user.email.blank")'));
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("fos_user.username.blank")'));
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("fos_user.password.blank")'));

        $data['registration[email]'] = 'bademail';
        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("fos_user.email.invalid")'));

        $data['registration[email]'] = 'user1@example.com';
        $data['registration[username]'] = 'user1';
        $data['registration[plainPassword][first]'] = 'user1';
        $data['registration[plainPassword][second]'] = 'user2';
        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);

        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("fos_user.email.already_used")'));
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("fos_user.username.already_used")'));
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("fos_user.password.mismatch")'));

        $data['registration[email]'] = 'user3@example.com';
        $data['registration[username]'] = 'user3';
        $data['registration[plainPassword][first]'] = 'user3';
        $data['registration[plainPassword][second]'] = 'user3';
        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);

        $this->assertTrue($client->getResponse()->isRedirect('/'));
        // Authentified
        $security = $client->getContainer()->get('security.context');
        $this->assertTrue($security->isGranted('ROLE_USER'));
    }

    /**
     * Test on unsubcribe action
     */
    public function testUnsubscribe()
    {
        $data = array(
            'login[email]' => 'user1',
            'login[password]' => 'user1'
        );
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);

        // Check authentification
        $security = $client->getContainer()->get('security.context');
        $this->assertTrue($security->isGranted('ROLE_USER'));

        $crawler = $client->request('GET', '/unsubscribe');

        // Confirmation submission
        $form = $crawler->filter('button[type="submit"]')->form();
        $client->enableProfiler();
        $crawler = $client->submit($form);

        // Email to alert that password has been changed
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
         // Check that an e-mail was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());
        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];
        $this->assertEquals('unsubscribe.email.subject', $message->getSubject());
        $this->assertEquals('unsubscribe.email.message', trim($message->getBody()));

        $crawler = $client->followRedirect();
        $crawler = $client->followRedirect();
        $security = $client->getContainer()->get('security.context');
        $this->assertFalse($security->isGranted('ROLE_USER'));

        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);
        $crawler = $client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("Bad credentials")'));
    }
}

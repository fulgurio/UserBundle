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
        $client = $this->getClient();
        $crawler = $client->request('GET', '/admin');
        $this->assertCount(1, $crawler->filter('a:contains("users_manager")'));
    }

    /**
     * Test users listing
     */
    public function testListAction()
    {
        $client = $this->getClient();
        $crawler = $client->request('GET', '/admin/users');
//        $this->printResult($client);
        $this->assertCount(2, $crawler->filter('tbody tr'));
    }

    /**
     * Test user removing
     */
    public function testBanAction()
    {
        $client = $this->getClient();
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
     * Test user resetting password
     */
    public function testResetPasswordAction()
    {
        $client = $this->getClient();
        $crawler = $client->request('GET', '/admin/users');
        $this->assertCount(2, $crawler->filter('tbody tr'));
        $resetPasswordLink = $crawler->filter('a[title="reset_password"]')->link();
        $crawler = $client->click($resetPasswordLink);

        $form = $crawler->filter('button:contains("form.confirmation.yes")')->form();
        $client->enableProfiler();
        $crawler = $client->submit($form);

        // Email to change password has been sent
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
         // Check that an e-mail was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());
        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];
        $this->assertEquals('resetting.email.subject', $message->getSubject());
        $this->assertEquals('resetting.email.message', trim($message->getBody()));
        $crawler = $client->followRedirect();
        $this->assertCount(1, $crawler->filter('div.alert-success:contains("reset_password_success_message")'));
    }

    /**
     * Test user removing
     */
    public function testRemoveAction()
    {
        $client = $this->getClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/admin/users');
        $this->assertCount(2, $crawler->filter('tbody tr'));
        $deleteLink = $crawler->filter('a[title="delete"]')->link();
        $crawler = $client->click($deleteLink);

        $form = $crawler->filter('button:contains("form.confirmation.yes")')->form();
        $crawler = $client->submit($form);
        $this->assertCount(1, $crawler->filter('tbody tr'));
    }

    /**
     * Get authentified client
     *
     * @return type
     */
    private function getClient()
    {
        return static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
    }
}

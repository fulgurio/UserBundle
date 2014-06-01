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

class ResettingControllerTest extends WebTestCase
{
    /**
     * Test of lost password form
     */
    public function testLostPassword()
    {
        $data = array('username' => '');
        $client = static::createClient();
        $crawler = $client->request('GET', '/resetting/request');

        $form = $crawler->filter('button[type="submit"]')->form();
        $client->enableProfiler();
        $crawler = $client->submit($form, $data);
        $this->assertCount(1, $crawler->filter('div.alert-danger li:contains("resetting.request.invalid_username")'));

        $data['username'] = 'user1';
        $form = $crawler->filter('button[type="submit"]')->form();
        $client->enableProfiler();
        $crawler = $client->submit($form, $data);

        // Email to change password has been sent
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
         // Check that an e-mail was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());
        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];
        $this->assertEquals('resetting.email.subject', $message->getSubject());
        $this->assertEquals('resetting.email.message', trim($message->getBody()));

        $crawler = $client->followRedirect();
        $this->assertCount(1, $crawler->filter('p:contains("resetting.check_email")'));

        //@todo : faire l'appel à l'url avec le token, puis mettr le nouveau mot de passe, et tester le nouveau login
    }
}

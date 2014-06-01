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

class ChangePasswordControllerTest extends WebTestCase
{
    /**
     * Test of profile edition
     */
    public function testChangePassword()
    {
        $data = array(
            'login[email]' => 'user2',
            'login[password]' => 'user2'
        );
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);

        $crawler = $client->request('GET', '/profile/change-password');
        $form = $crawler->filter('button[type="submit"]')->form();
        $newPassword = 'newPassword';
        $client->enableProfiler();
        $crawler = $client->submit($form,
                array(
                    'changePassword[current_password]' => $data['login[password]'],
                    'changePassword[plainPassword][first]' => $newPassword,
                    'changePassword[plainPassword][second]' => $newPassword
                    )
                );

        // Email to alert that password has been changed
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
         // Check that an e-mail was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());
        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];
        $this->assertEquals('change_password.email.subject', $message->getSubject());
        $this->assertEquals('change_password.email.message', trim($message->getBody()));

        $data['login[password]'] = $newPassword;
        $crawler = $client->request('GET', '/logout');
        $crawler = $client->followRedirect();
        $security = $client->getContainer()->get('security.context');
        $this->assertFalse($security->isGranted('ROLE_USER'));

        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form();

        $crawler = $client->submit($form, $data);

        // Check authentification
        $security = $client->getContainer()->get('security.context');
        $this->assertTrue($security->isGranted('ROLE_USER'));
    }
}

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

class ProfileControllerTest extends WebTestCase
{
    /**
     * Test of profile edition
     */
    public function testEditProfile()
    {
        $data = array(
            'login[email]' => 'user2',
            'login[password]' => 'user2'
        );
        $client = static::createClient();
        $client->followRedirects(TRUE);

        $crawler = $client->request('GET', '/login');

        $form = $crawler->filter('button[type="submit"]')->form();
        $crawler = $client->submit($form, $data);

        $crawler = $client->request('GET', '/profile/');
        $this->assertCount(1, $crawler->filter('p:contains("profile.show.username: user2")'));

        $crawler = $client->request('GET', '/profile/edit');
        $form = $crawler->filter('button[type="submit"]')->form();
        $data['login[email]'] = 'user2change@example.com';
        $crawler = $client->submit($form,
                array(
                    'profil[email]' => $data['login[email]'],
                    'profil[username]' => 'user2change',
                    'profil[current_password]' => $data['login[password]']
                    )
                );

        $crawler = $client->request('GET', '/logout');
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

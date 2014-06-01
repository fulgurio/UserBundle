<?php
/*
 * This file is part of the FulgurioUserBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\UserBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\TwigSwiftMailer as BaseTwigSwiftMailer;

/**
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 */
class TwigSwiftMailer extends BaseTwigSwiftMailer
{
    /**
     * Send an email when user changes his password.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     */
    public function sendPasswordHasBeenChangedMessage(UserInterface $user)
    {
        $template = $this->parameters['template']['change_password'];
        $context = array('user' => $user);
        $this->sendMessage(
                $template,
                $context,
                $this->parameters['from_email']['change_password'],
                $user->getEmail()
                );
    }
}

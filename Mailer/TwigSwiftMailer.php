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

    /**
     * Send an email when user unregisters or with administration panel
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     */
    public function sendUserHasUnsubscribedMessage(UserInterface $user)
    {
        if ($this->parameters['enabled']['unsubscribe'] === FALSE)
        {
            return;
        }
        $template = $this->parameters['template']['unsubscribe'];
        $context = array('user' => $user);
        $this->sendMessage(
                $template,
                $context,
                $this->parameters['from_email']['unsubscribe'],
                $user->getEmail()
                );
    }

    /**
     * Send an email when user unregisters or with administration panel
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     * @param boolean $isUnbanned
     * @param string message
     */
    public function sendAccountHasBeenBannedMessage(UserInterface $user, $isUnbanned, $message)
    {
        if ($this->parameters['enabled']['ban'] === FALSE)
        {
            return;
        }
        $template = $this->parameters['template'][$isUnbanned ? 'unban' : 'ban'];
        $context = array(
            'user' => $user,
            'message' => $message);
        $this->sendMessage($template, $context, $this->parameters['from_email']['ban'], $user->getEmail());
    }

    /**
     * Send an email to user
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     * @param string $subject
     * @param string $content
     */
    public function sendContactMessage(UserInterface $user, $subject, $content)
    {
        $context = array(
            'subject' => $subject,
            'content' => $content
            );
        $this->sendMessage($this->parameters['template']['contact'],
                $context,
                $this->parameters['from_email']['contact'],
                $user->getEmail());
    }
}

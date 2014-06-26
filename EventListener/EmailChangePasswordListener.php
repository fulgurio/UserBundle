<?php
/*
 * This file is part of the FulgurioUserBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 */
class EmailChangePasswordListener implements EventSubscriberInterface
{
    /**
     * @var type FOS\UserBundle\Mailer\MailerInterface
     */
    private $mailer;


    /**
     * Constructor
     *
     * @param \FOS\UserBundle\Mailer\MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Add events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::CHANGE_PASSWORD_COMPLETED => 'onChangePasswordCompleted',
            FOSUserEvents::RESETTING_RESET_COMPLETED => 'onChangePasswordCompleted'
        );
    }

    /**
     * Event launched on FOSUserEvents::CHANGE_PASSWORD_COMPLETED
     *
     * @param \FOS\UserBundle\Event\FilterUserResponseEvent $event
     */
    public function onChangePasswordCompleted(FilterUserResponseEvent $event)
    {
        $this->mailer->sendPasswordHasBeenChangedMessage($event->getUser());
    }
}

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
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 */
class ChangePasswordListener implements EventSubscriberInterface
{
    /**
     * @var type Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    /**
     * @var Symfony\Bundle\FrameworkBundle\Translation\Translator;
     */
    private $translator;


    /**
     * Constructor
     *
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param \Symfony\Bundle\FrameworkBundle\Translation\Translator $translator
     */
    public function __construct(Session $session, Translator $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * Add events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::RESETTING_RESET_COMPLETED => 'onResettingResetCompleted'
        );
    }

    /**
     * Event launched on FOSUserEvents::CHANGE_PASSWORD_COMPLETED
     *
     * @param \FOS\UserBundle\Event\FilterUserResponseEvent $event
     */
    public function onResettingResetCompleted(FilterUserResponseEvent $event)
    {
        $this->session->getFlashBag()->add(
                'notice',
                $this->translator->trans('change_password.notice')
        );
    }
}

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
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 */
class RegistrationListener implements EventSubscriberInterface
{
    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    private $container;


    /**
     * Constructor
     *
     * @param Symfony\Component\DependencyInjection\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Add events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
                FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    /**
     * Event call by FOSUser bundle on registration success
     *
     * @param FormEvent $event
     * @todo : add in config the redirect url, and the flash message
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        $event->setResponse(
            new RedirectResponse(
                    $this->container->get('router')->generate('fulgurio_user_default_index')
                    )
                );
        $this->container->get('session')->getFlashBag()->add(
                'notice',
                $this->container->get('translator')->trans('form.register.success')
        );
    }
}
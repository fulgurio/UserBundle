<?php
/*
 * This file is part of the FulgurioUserBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FulgurioUserExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->loadChangePassword($config['change_password'], $container, $loader, $config['from_email']);
        $this->loadUnsubscribe($config['unsubscribe'], $container, $loader, $config['from_email']);
        $this->loadBan($config, $container, $loader, $config['from_email']);
        $this->loadAvatar($config['avatar'], $container, $loader);
    }

    /**
     * Change password configuration
     *
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\DependencyInjection\Loader\YamlFileLoader $loader
     * @param array $fromEmail
     */
    private function loadChangePassword(array $config, ContainerBuilder $container, YamlFileLoader $loader, array $fromEmail)
    {
        if ($config['email']['enabled'])
        {
            $loader->load('email_change_password.yml');
        }
        if (isset($config['email']['from_email']))
        {
            $fromEmail = $config['email']['from_email'];
            unset($config['email']['from_email']);
        }
        $container->setParameter('fulgurio_user.change_password.email.from_email', array($fromEmail['address'] => $fromEmail['sender_name']));
        $container->setParameter('fulgurio_user.change_password.email.template', $config['email']['template']);
    }

    /**
     * Unsubscribe configuration
     *
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\DependencyInjection\Loader\YamlFileLoader $loader
     * @param array $fromEmail
     */
    private function loadUnsubscribe(array $config, ContainerBuilder $container, YamlFileLoader $loader, array $fromEmail)
    {
        $container->setParameter('fulgurio_user.unsubscribe.email.enabled', $config['email']['enabled']);
        if (isset($config['email']['from_email']))
        {
            $fromEmail = $config['email']['from_email'];
            unset($config['email']['from_email']);
        }
        $container->setParameter('fulgurio_user.unsubscribe.email.from_email', array($fromEmail['address'] => $fromEmail['sender_name']));
        $container->setParameter('fulgurio_user.unsubscribe.email.template', $config['email']['template']);
    }

    /**
     * Ban / unban configuration
     *
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\DependencyInjection\Loader\YamlFileLoader $loader
     * @param array $fromEmail
     */
    private function loadBan(array $config, ContainerBuilder $container, YamlFileLoader $loader, array $fromEmail)
    {
        $container->setParameter('fulgurio_user.ban.email.enabled', $config['ban']['email']['enabled']);
        if (isset($config['ban']['email']['from_email']))
        {
            $fromEmail = $config['ban']['email']['from_email'];
            unset($config['ban']['email']['from_email']);
        }
        $container->setParameter('fulgurio_user.ban.email.from_email', array($fromEmail['address'] => $fromEmail['sender_name']));
        $container->setParameter('fulgurio_user.ban.email.template', $config['ban']['email']['template']);
        $container->setParameter('fulgurio_user.unban.email.template', $config['unban']['email']['template']);
    }

    /**
     * Avatar configuration
     *
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param \Symfony\Component\DependencyInjection\Loader\YamlFileLoader $loader
     * @param array $fromEmail
     */
    private function loadAvatar(array $config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $container->setParameter('fulgurio_user.avatar.enabled', $config['enabled']);
        $container->setParameter('fulgurio_user.avatar.default_avatar', $config['defaultAvatar']);
        $container->setParameter('fulgurio_user.avatar.size', $config['size']);
    }
}

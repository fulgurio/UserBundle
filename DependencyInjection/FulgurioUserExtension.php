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

        if (!empty($config['change_password']))
        {
            $this->loadChangePassword($config['change_password'], $container, $loader, $config['from_email']);
        }
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
}

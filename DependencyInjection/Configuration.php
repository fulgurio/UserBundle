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

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fulgurio_user');
        $rootNode
                ->children()
                    ->arrayNode('from_email')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('address')->defaultValue('webmaster@example.com')->cannotBeEmpty()->end()
                            ->scalarNode('sender_name')->defaultValue('webmaster')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end();
        $this->addChangePasswordSection($rootNode);
        $this->addContactSection($rootNode);
        $this->addUnsubscribeSection($rootNode);
        $this->addBanSection($rootNode);
        $this->addAvatarSection($rootNode);
        return $treeBuilder;
    }

    /**
     * Change password configuration
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addChangePasswordSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('change_password')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('email')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
                                ->scalarNode('template')->defaultValue('FulgurioUserBundle:ChangePassword:email.html.twig')->end()
                                ->arrayNode('from_email')
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('address')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('sender_name')->isRequired()->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Contact configuration
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addContactSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('contact')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('email')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
                                ->scalarNode('template')->defaultValue('FulgurioUserBundle:Admin:contact_email.html.twig')->end()
                                ->arrayNode('from_email')
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('address')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('sender_name')->isRequired()->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Unsubscribe configuration
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addUnsubscribeSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('unsubscribe')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('email')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
                                ->scalarNode('template')->defaultValue('FulgurioUserBundle:Registration:email_unsubscribe.html.twig')->end()
                                ->arrayNode('from_email')
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('address')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('sender_name')->isRequired()->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Ban configuration
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addBanSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('ban')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('email')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
                                ->scalarNode('template')->defaultValue('FulgurioUserBundle:Admin:email_ban.html.twig')->end()
                                ->arrayNode('from_email')
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('address')->isRequired()->cannotBeEmpty()->end()
                                        ->scalarNode('sender_name')->isRequired()->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('unban')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('email')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('template')->defaultValue('FulgurioUserBundle:Admin:email_unban.html.twig')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Avatar configuration
     *
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     */
    private function addAvatarSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('avatar')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->booleanNode('enabled')->defaultFalse()->end()
                        ->scalarNode('defaultAvatar')->defaultValue('bundles/fulguriouser/images/avatar.png')->end()
                    ->end()
                ->end()
            ->end();
    }
}

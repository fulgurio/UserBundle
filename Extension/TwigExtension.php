<?php
/*
 * This file is part of the FulgurioUserBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\UserBundle\Extension;

use Fulgurio\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\Container;

/**
 * @author Vincent GUERARd <v.guerard@fulgurio.net>
 */
class TwigExtension extends \Twig_Extension {
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
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            'avatar' =>             new \Twig_Function_Method($this, 'avatar', array('is_safe' => array('html'))),
            'isAvatarEnabled' =>    new \Twig_Function_Method($this, 'isAvatarEnabled'),
        );
    }

    /**
     * Display avatar user
     *
     * @param Fulgurio\UserBundle\Entity\User $user
     *
     * @return string
     */
    public function avatar(User $user)
    {
        if ($user->getAvatar() != '')
        {
            $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
            return $helper->asset($user, 'avatarFile');
        }
        else
        {
            return $this->container
                    ->get('templating.helper.assets')
                    ->getUrl($this->container->getParameter('fulgurio_user.avatar.default_avatar'));
        }
    }

    /**
     * Check in config if avatar functionality is enabled
     *
     * @return boolean
     */
    public function isAvatarEnabled()
    {
        return $this->container->getParameter('fulgurio_user.avatar.enabled');
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'fulgurio_user_extension';
    }
}
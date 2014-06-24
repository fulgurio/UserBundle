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

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
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
            'getDefaultAvatar' =>    new \Twig_Function_Method($this, 'getDefaultAvatar'),
            'isProfileWithPasswordEdition' =>    new \Twig_Function_Method($this, 'isProfileWithPasswordEdition'),
            'useOnePasswordFieldForRegistration' => new \Twig_Function_Method($this, 'useOnePasswordFieldForRegistration'),
            'isOAuthServiceEnabled' =>    new \Twig_Function_Method($this, 'isOAuthServiceEnabled')
        );
    }

    /**
     * Display avatar user
     *
     * @param FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    public function avatar(UserInterface $user)
    {
        if ($user->getAvatar() != '')
        {
            if (substr($user->getAvatar(), 0, 7) == 'http://'
                    || substr($user->getAvatar(), 0, 8) == 'https://')
            {
                return $user->getAvatar();
            }
            else
            {
                $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
                return $helper->asset($user, 'avatarFile');
            }
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
     * Check in config if avatar functionality is enabled
     *
     * @return boolean
     */
    public function getDefaultAvatar()
    {
        return $this->container->getParameter('fulgurio_user.avatar.default_avatar');
    }

    /**
     * Check in config if we allows password edition in profile page
     *
     * @return boolean
     */
    public function isProfileWithPasswordEdition()
    {
        return $this->container->getParameter('fos_user.profile.form.type') == 'fulgurio_user_profile_with_password_edition';
    }

    /**
     * Check if you will use one field for password (without repeat)
     *
     * @return boolean
     */
    public function useOnePasswordFieldForRegistration()
    {
        return $this->container->getParameter('fos_user.registration.form.type') == 'fulgurio_user_registration';
    }

    /**
     * Check in config if facebook connector is configured
     *
     * @return boolean
     */
    public function isOAuthServiceEnabled($serviceName)
    {
        $services = $this->container->getParameter('hwi_oauth.resource_owners');
        return is_array($services) && in_array($serviceName, $services);
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
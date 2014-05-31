<?php
/*
 * This file is part of the FulgurioUserBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

/**
 * Controller managing the registration
 *
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 */
class RegistrationController extends BaseController
{
    /**
     * If user is already connected, we redirect him to homepage
     *
     * @see FOS\UserBundle\Controller\RegistrationController
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request)
    {
        if (TRUE === $this->container->get('security.context')->isGranted('ROLE_USER'))
        {
            $url = $this->container->get('router')->generate('fulgurio_user_default_index');
            return new RedirectResponse($url);
        }
        return parent::registerAction($request);
    }
}

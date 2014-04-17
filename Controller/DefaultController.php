<?php
namespace Fulgurio\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/unsubscribe")
     * @Template("FulgurioUserBundle:Registration:unsubscribe.html.twig")
     */
    public function unsubscribeAction()
    {
        $request = $this->get('request');
        if ('POST' === $request->getMethod()
                && $request->get('confirm') == 'yes')
        {
            $userManager = $this->container->get('fos_user.user_manager');
            $user = $this->getUser();
            $user->setEnabled(FALSE);
//             $userManager->updateUser($user);
            $userManager->deleteUser($this->getUser());
            return $this->redirect($this->get('router')->generate('fos_user_security_logout'));

        }
        return array();
    }
}

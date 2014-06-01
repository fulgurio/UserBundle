<?php
namespace Fulgurio\UserBundle\Controller;

//use Fulgurio\UserBundle\Entity\User;
//use FOS\UserBundle\Form\Type\RegistrationFormType as UserType;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserManagerController extends Controller
{
    /**
     * @Route("/admin")
     * @Template("FulgurioUserBundle:Admin:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/admin/users")
     * @Template("FulgurioUserBundle:Admin:list.html.twig")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $users= $em->getRepository('FulgurioUserBundle:User')
                ->findAllWithPaginator(
                        $paginator,
                        $this->get('request')->get('page', 1),
                        $this->get('request')->get('q')
                        );
        return array('users' => $users);
    }

    /**
     * @Route("/admin/users/{userId}/remove")
     * @Template("FulgurioUserBundle:Admin:.html.twig")
     */
    public function removeAction($userId)
    {
        $user = $this->getSpecifiedUser($userId);
        $request = $this->container->get('request');
        if ($request->get('confirm') === 'yes')
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans('delete_success_message', array(), 'admin')
            );
            return $this->redirect($this->generateUrl('fulgurio_user_usermanager_list'));
        }
        else if ($request->get('confirm') === 'no')
        {
            // @todo : if pagination; it s better to come back a the same page
            return $this->redirect($this->generateUrl('fulgurio_user_usermanager_list'));
        }
        $templateName = $request->isXmlHttpRequest() ? 'FulgurioUserBundle:Admin:confirmAjax.html.twig' : 'FulgurioUserBundle:Admin:confirm.html.twig';
        return $this->render($templateName, array(
                'action' => $this->generateUrl('fulgurio_user_usermanager_remove', array('userId' => $userId)),
                'confirmationMessage' => $this->get('translator')->trans('delete_confirm_message', array('%TITLE%' => $user->getUsername()), 'admin'),
        ));
}

    /**
     * Get user from given id
     *
     * @param number $userId
     * @return User
     * @throws NotFoundHttpException
     */
    private function getSpecifiedUser($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('FulgurioUserBundle:User')->find($userId);
        if (is_null($user))
        {
            throw $this->createNotFoundException();
        }
        return ($user);
    }
}

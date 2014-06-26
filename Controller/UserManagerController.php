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
        $userClassName = $this->container->getParameter('fos_user.model.user.class');
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $users= $em->getRepository($userClassName)
                ->findAllWithPaginator(
                        $paginator,
                        $this->get('request')->get('page', 1),
                        $this->get('request')->get('q')
                        );
        return array(
            'users' => $users,
            'changePasswordTTL' => $this->container->getParameter('fos_user.resetting.token_ttl')
                );
    }

    /**
     * @Route("/admin/users/{userId}/remove")
     */
    public function removeAction($userId)
    {
        $user = $this->getSpecifiedUser($userId);
        $request = $this->get('request');
        if ($request->get('confirm') === 'yes')
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            $this->container->get('fulgurio_user.mailer')->sendUserHasUnsubscribedMessage($user);
            $this->get('session')->getFlashBag()->add('notice',$this->get('translator')->trans('delete_success_message', array(), 'admin'));
            return $this->redirect($this->generateUrl('fulgurio_user_usermanager_list'));
        }
        else if ($request->get('confirm') === 'no')
        {
            return $this->redirect($this->generateUrl('fulgurio_user_usermanager_list'));
        }
        $templateName = $request->isXmlHttpRequest() ? 'FulgurioUserBundle:Admin:confirmAjax.html.twig' : 'FulgurioUserBundle:Admin:confirm.html.twig';
        return $this->render($templateName, array(
                'action' => $this->generateUrl('fulgurio_user_usermanager_remove', array('userId' => $userId)),
                'confirmationMessage' => $this->get('translator')->trans('delete_confirm_message', array('%TITLE%' => $user->getUsername()), 'admin'),
        ));
    }

    /**
     * @Route("/admin/users/{userId}/send-contact-email")
     * @Template("FulgurioUserBundle:Admin:send_contact_email.html.twig")
     */
    public function sendContactEmailAction($userId)
    {
        $request = $this->getRequest();
        $user = $this->getSpecifiedUser($userId);
        $data = array('user' => $user, 'errors' => array());
        if ($request->getRealMethod() == 'POST')
        {
            $subject = trim($request->get('subject'));
            $message = trim($request->get('message'));
            if ($subject == '')
            {
                $data['errors'][] = $this->get('translator')->trans('contact_email.form.subject_required', array(), 'admin');
            }
            if ($message == '')
            {
                $data['errors'][] = $this->get('translator')->trans('contact_email.form.message_required', array(), 'admin');
            }
            if (empty($data['errors']))
            {
                $this->container->get('fulgurio_user.mailer')->sendContactMessage($user, $subject, $message);
                $this->get('session')->getFlashBag()->add('notice',$this->get('translator')->trans('contact_email.success_message', array(), 'admin'));
                return $this->redirect($this->generateUrl('fulgurio_user_usermanager_list'));
            }
        }
        return $data;
    }

    /**
     * @Route("/admin/users/{userId}/ban")
     * @Template("FulgurioUserBundle:Admin:ban.html.twig")
     */
    public function banAction($userId)
    {
        return $this->banOrUnban($userId, FALSE);
    }

    /**
     * @Route("/admin/users/{userId}/unban")
     * @Template("FulgurioUserBundle:Admin:ban.html.twig")
     */
    public function unbanAction($userId)
    {
        return $this->banOrUnban($userId, TRUE);
    }

    /**
     * Do ban or unban action
     *
     * @param number $userId
     * @param boolean $doUnban
     * @return array|RedirectResponse
     */
    private function banOrUnban($userId, $doUnban)
    {
        $user = $this->getSpecifiedUser($userId);
        $request = $this->getRequest();
        if ($request->get('confirm') === 'yes')
        {
            $user->setEnabled($doUnban);
            $this->container->get('fos_user.user_manager')->updateUser($user);
            $this->container->get('fulgurio_user.mailer')->sendAccountHasBeenBannedMessage($user, $doUnban, trim($request->get('message')));
            $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans($doUnban ? 'unban.unban_success_message' : 'ban.ban_success_message', array(), 'admin'));
            return $this->redirect($this->generateUrl('fulgurio_user_usermanager_list'));
        }
        else if ($request->get('confirm') === 'no')
        {
            return $this->redirect($this->generateUrl('fulgurio_user_usermanager_list'));
        }
        return array(
            'action' => $this->generateUrl($doUnban ? 'fulgurio_user_usermanager_unban' : 'fulgurio_user_usermanager_ban', array('userId' => $userId)),
            'confirmationMessage' => $this->get('translator')->trans($doUnban ? 'unban.unban_confirm_message' : 'ban.ban_confirm_message', array('%TITLE%' => $user->getUsername()), 'admin')
                );
    }

    /**
     * @see FOS\UserBundle\Controller\ResettingController.sendEmailAction
     * @Route("/admin/users/{userId}/reset-password")
     */
    public function resetPasswordAction($userId)
    {
        $user = $this->getSpecifiedUser($userId);
        if ($user->isEnabled() === FALSE)
        {
            // User is not enabled, you can not access to this URL without cheating
            throw new AccessDeniedException();
        }
        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl')))
        {
            // An email has been send for password resetting. You can not access to this URL without cheating
            throw new AccessDeniedException();
        }
        $request = $this->get('request');
        if ($request->get('confirm') === 'yes')
        {
            if (null === $user->getConfirmationToken()) {
                /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }

            $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
            $user->setPasswordRequestedAt(new \DateTime());
            $this->container->get('fos_user.user_manager')->updateUser($user);

            $this->get('session')->getFlashBag()->add('notice',$this->get('translator')->trans('reset_password_success_message', array('%EMAIL%' => $user->getEmail()), 'admin'));
            return $this->redirect($this->generateUrl('fulgurio_user_usermanager_list'));
        }
        else if ($request->get('confirm') === 'no')
        {
            // @todo : if pagination; it s better to come back a the same page
            return $this->redirect($this->generateUrl('fulgurio_user_usermanager_list'));
        }
        $templateName = $request->isXmlHttpRequest() ? 'FulgurioUserBundle:Admin:confirmAjax.html.twig' : 'FulgurioUserBundle:Admin:confirm.html.twig';
        return $this->render($templateName, array(
                'action' => $this->generateUrl('fulgurio_user_usermanager_resetpassword', array('userId' => $userId)),
                'confirmationMessage' => $this->get('translator')->trans('reset_password_confirm_message', array('%TITLE%' => $user->getUsername()), 'admin'),
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

<?php
/*
 * This file is part of the FulgurioUserBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Fulgurio\UserBundle\Form\Type\ProfileFormType as BaseProfileFormType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/**
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 */
class ProfileWithPasswordEditionFormType extends BaseProfileFormType
{
    /**
     * Build form
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false
        ));
        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.new_password'),
            'second_options' => array('label' => 'form.new_password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $data = $event->getData();
            if ($data['plainPassword']['first'] != '' || $data['plainPassword']['second'] != '')
            {
                $form = $event->getForm();
                if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword'))
                {
                    $constraint = new UserPassword();
                }
                else
                {
                    // Symfony 2.1 support with the old constraint class
                    $constraint = new OldUserPassword();
                }
                $form->add('current_password', 'password', array(
                    'label' => 'form.current_password',
                    'translation_domain' => 'FOSUserBundle',
                    'mapped' => false,
                    'constraints' => $constraint,
                    ));
                }
            });
    }

    /**
     * Return form name
     *
     * @return string
     */
    public function getName()
    {
        return 'fulgurio_user_profile_with_password_edition';
    }
}
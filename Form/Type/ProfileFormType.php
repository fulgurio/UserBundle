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
use FOS\UserBundle\Form\Type\ProfileFormType as BaseProfileFormType;

/**
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 */
class ProfileFormType extends BaseProfileFormType
{
    /**
     * Build form
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildUserForm($builder, $options);
        $builder->add('avatarFile', 'file');
    }

    /**
     * Return form name
     *
     * @return string
     */
    public function getName()
    {
        return 'fulgurio_user_profile';
    }
}
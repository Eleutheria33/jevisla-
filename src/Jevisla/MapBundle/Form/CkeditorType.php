<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\MapBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;

class CkeditorType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
            'attr' => array('class' => 'ckeditor'),
            )
        );
    }

    public function getParent()
    {
        return TextareaType::class;
    }

    public function getName()
    {
        return 'ckeditor';
    }
}

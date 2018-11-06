<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\MapBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FicheUserGoogleMapType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo1', TextType::class, array('required' => false))
            ->add('devise', TextType::class, array('required' => false))
            ->add('adresse', TextType::class, array('required' => false))
            ->add('texte', TextareaType::class, array('trim' => array('class' => 'Ckeditor')))
            ->add('Avatar', AvatarType::class, array('required' => false))
            ->add('phone', IntegerType::class, array('required' => false))
            ->add('mail', TextType::class, array('required' => false))
            ->add('valider', SubmitType::class);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $ficheUserGoogleMap = $event->getData();
                if (null === $ficheUserGoogleMap) {
                    return;
                }
            }
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
            'data_class' => 'Jevisla\MapBundle\Entity\FicheUserGoogleMap',
            )
        );
    }
}

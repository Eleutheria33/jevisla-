<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add(
                'civilite',
                ChoiceType::class,
                array(
                        'choices' => array(
                            'Madame' => 'Madame',
                            'Mademoiselle' => 'Mademoiselle',
                            'Monsieur' => 'Monsieur',
                    ), )
            )
            ->add('prenom', TextType::class)
            ->add('pseudo', TextType::class)
            ->add('numeroAd', NumberType::class)
            ->add(
                'voieAd',
                ChoiceType::class,
                array(
                        'choices' => array(
                            'Allée' => 'Allée',
                            'Avenue' => 'Avenue',
                            'Boulevard' => 'Boulevard',
                            'Chemin' => 'Chemin',
                            'Cours' => 'Cours',
                            'Impasse' => 'Impasse',
                            'Rue' => 'Rue',
                            'Sentier' => 'Sentier',
                            'Voie' => 'Voie',
                     ), )
            )
            ->add('nomVoieAd', TextType::class)
            ->add('villeAd', TextType::class)
            ->add('codePostal', NumberType::class)
            ->add('latitude', NumberType::class)
            ->add('longitude', NumberType::class)
            ->add('portable', NumberType::class)
            ->add('image', ImageType::class, array('required' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'jevisla_user_registration';
    }
}

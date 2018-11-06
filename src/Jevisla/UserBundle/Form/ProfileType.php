<?php

/**
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jevisla\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProfileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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

    // BC for SF < 3.0

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'jevisla_user_profile';
    }
}

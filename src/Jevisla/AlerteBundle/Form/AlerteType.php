<?php

namespace Jevisla\AlerteBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlerteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern = 'D%';
        $builder
            ->add('date', DateTimeType::class)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('published', CheckboxType::class, array('required' => false))
            ->add(
                'imageAlerte',
                ImageAlerteType::class,
                array(
                'required' => false,
                )
            )
            ->add(
                'categories',
                EntityType::class,
                array(
                    'required' => true,
                    'class' => 'JevislaAlerteBundle:Category',
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => false,
                )
            )
            ->add('enregistrer', SubmitType::class);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $alerte = $event->getData();

                if (null === $alerte) {
                    return;
                }

                if (!$alerte->getPublished() || null === $alerte->getId()) {
                    $event->getForm()->add('published', CheckboxType::class, array('required' => false));
                } else {
                    $event->getForm()->remove('published');
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
            'data_class' => 'Jevisla\AlerteBundle\Entity\Alerte',
            )
        );
    }
}

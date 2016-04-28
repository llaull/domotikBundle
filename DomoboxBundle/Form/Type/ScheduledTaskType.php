<?php

namespace Domotique\DomoboxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ScheduledTaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('created')
            ->add('start', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yy HH:mm',
                'attr' => ['class' => 'form-control datetime'])
            )
            ->add('stop', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yy HH:mm',
                'attr' => ['class' => 'daterange-btn'])
            )
            ->add('action')
            ->add('valeur')
            ->add('module')
            ->add('user')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Domotique\DomoboxBundle\Entity\ScheduledTask'
        ));
    }
}

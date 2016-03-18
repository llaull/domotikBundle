<?php

namespace Domotique\ReseauBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('created', 'datetime')
            ->add('modified', 'datetime')
            ->add('name')
            ->add('adressMac')
            ->add('adressIpv4')
            ->add('location')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Domotique\ReseauBundle\Entity\Module'
        ));
    }
}

<?php

namespace DurabolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataEsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('receivername', null, array('label' => 'Nombre de Empresa'))
            ->add('receiverphone', null, array('label' => 'Teléfono'))
            ->add('receiveradress', null, array('label' => 'Dirección'))
            ->add('receiverprovince', null, array('label' => 'Provincia'))
            ->add('receivercity', null, array('label' => 'Ciudad'))
            ->add('receiverpostcode', null, array('label' => 'Código Postal'))
            ->add('receivershuihao', null, array('label' => 'CIF'))
            ->add('receivergerenshui', null, array('label' => 'Eres Autónomo?'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DurabolBundle\Entity\Data'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'durabolbundle_data';
    }


}

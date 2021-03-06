<?php

namespace DurabolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Nombre CN'))
            ->add('nameEs', null, array('label' => 'Nombre ES'))
            ->add('price', null, array('label' => 'Precio'))
            ->add('unit', null, array('label' => 'Unidad'))
            ->add('code', null, array('label' => 'Código'))
            ->add('foto', FileType::class, array(
                'data_class' => null,
                'required' => false,
            ))
            ->add('isShow', null, array('label' => 'Mostrado'))
            ->add('isSale', null, array('label' => 'Descuento'))
            ->add('discountPrice', null, array('label' => 'Precio con Descuento'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DurabolBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'durabolbundle_product';
    }


}

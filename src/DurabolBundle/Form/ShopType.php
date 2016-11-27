<?php

namespace DurabolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ShopType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => '店铺名'))
            ->add('nameShort', null, array('label' => '店铺简称'))
            ->add('description', null, array('label' => '描述'))
            ->add('minCoste', null, array('label' => '最低消费'))
            ->add('foto', FileType::class, array(
                'data_class' => null,
                'required' => false,
                'label' => '代表图片',
            ))
    ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DurabolBundle\Entity\Shop'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'durabolbundle_shop';
    }


}

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
            ->add('nameEs', null, array('label' => '店铺名西语'))
            ->add('nameShort', null, array('label' => '店铺简称'))
            ->add('description', null, array('label' => '描述'))
            ->add('descriptionEs', null, array('label' => '描述西语'))
            ->add('minCoste', null, array('label' => '最低消费'))
            ->add('foto', FileType::class, array(
                'data_class' => null,
                'required' => false,
                'label' => '代表图片',
            ))
            ->add('turn', null, array('label' => '排列顺序,数字越大排列越前'))
            ->add('isTop', null, array('label' => '是否支持货到付款'))
            ->add('isPayOnline', null, array('label' => '是否支持在线付款'))
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

<?php

namespace DurabolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderInfoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('orderDate')->add('goodsFee')->add('shipFee')->add('totalPrice')->add('payType')->add('receiverName')->add('receierPhone')->add('receiverAdress')->add('receiverCity')->add('receiverProvince')->add('receiverPostcode')->add('isPayed')->add('isSended')->add('isOver')->add('state')->add('envio')->add('shipmode')->add('user')        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DurabolBundle\Entity\OrderInfo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'durabolbundle_orderinfo';
    }


}

<?php

namespace DurabolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('receivername', null, array('label' => '姓名/公司名'))
            ->add('receiverphone', null, array('label' => '电话'))
            ->add('receiveradress', null, array('label' => '地址'))
            ->add('receiverprovince', null, array('label' => '省份'))
            ->add('receivercity', null, array('label' => '城市'))
            ->add('receiverpostcode', null, array('label' => '邮编'))
            ->add('receivershuihao', null, array('label' => '税号'))
            ->add('receivergerenshui', null, array('label' => '是否个体户客户'))
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

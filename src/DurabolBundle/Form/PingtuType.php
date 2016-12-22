<?php

namespace DurabolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PingtuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('foto', FileType::class, array(
                'data_class' => null,
                'required' => false,
                'label' => '六格功能图片',
            ))
            ->add('routing', ChoiceType::class, array(
                'label' => '链接地点',
                'choices' => array(
                    'store' => '搜索店铺',
                    'carts' => '购物车',
                    'order_pedido' => '查看订单',
                    'font_info' => '用户须知、分享网站',
                    'shop' => '单个店铺',
                ),
                'required' => true,
                'attr' => array('class' => 'routing'),
            ))
            ->add('shopId', EntityType::class, array(
                'required' => false,
                'label' => '链接店铺ID',
                'class' => 'DurabolBundle:Shop',
                'property' => 'name',
                'label_attr' => array('class' => 'shopId'),
                'attr' => array('class' => 'shopId'),
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DurabolBundle\Entity\Pingtu'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'durabolbundle_pingtu';
    }


}

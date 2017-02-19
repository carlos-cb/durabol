<?php

namespace DurabolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null, array('label' => '标题'))
                ->add('author', null, array('label' => '作者'))
                ->add('content', null, array(
                    'attr' => array(
                        'class' => 'tinymce',
                    ),
                    'label' => '内容',
                    'required' => false,
                ))
                ->add('isEs', null, array('label' => '是否西语文章'))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DurabolBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'durabolbundle_post';
    }


}

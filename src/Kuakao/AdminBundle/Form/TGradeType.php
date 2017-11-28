<?php

namespace Kuakao\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TGradeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year')
            ->add('username')
            ->add('school')
            ->add('major')
            ->add('zzfs')
            ->add('wyfs')
            ->add('ywk1')
            ->add('ywk2')
            ->add('adminuser')
            ->add('addtime')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Kuakao\AdminBundle\Entity\TGrade'
        ));
    }
}

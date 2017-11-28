<?php

namespace Kuakao\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TSpecialType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('image','file')
            ->add('content')
            ->add('url')
            ->add('description','textarea')
            //->add('username')
            //->add('time')
            //->add('status','choice', array('choices'=>array(1=>'启用', 0=>'禁用')))
            ->add('status')
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"sub")))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Kuakao\AdminBundle\Entity\TSpecial'
        ));
    }
}

<?php

namespace Kuakao\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TQuizType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('icon','file')
            ->add('title')
            ->add('subtitle')
            ->add('topicNum')
            ->add('optionNum')
            ->add('presonNum')
            ->add('submit','submit',array("attr"=>array("formnovalidate"=>"formnovalidate","value"=>"sub")))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Kuakao\AdminBundle\Entity\TQuiz'
        ));
    }
}

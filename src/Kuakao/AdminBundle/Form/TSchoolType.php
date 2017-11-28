<?php

namespace Kuakao\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TSchoolType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('logo')
            ->add('ndxs')
            ->add('pic')
            ->add('rank')
            ->add('gzrs')
            ->add('province')
            ->add('is211')
            ->add('is985')
            ->add('isZhx')
            ->add('isYjs')
            ->add('area_top')
            ->add('trade_top')
            ->add('average_wage')
            ->add('about')
            ->add('jyl')
            ->add('school_type')
            ->add('comes_under')
            ->add('userid')
            ->add('inputtime')
            ->add('updatetime')
            ->add('school_level')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Kuakao\AdminBundle\Entity\TSchool',
            'error_mapping' => array(
                'matchingCityAndZipCode' => 'city',
            ),
        ));
    }
}

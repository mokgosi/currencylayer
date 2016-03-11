<?php

namespace ApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyOrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currency','text')
            ->add('exchangeRate','number')
            ->add('surchargeRate','number')
            ->add('amountPurchased','number')
            ->add('surchargeAmount','number')
            ->add('amountPaid','number')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ApiBundle\Entity\CurrencyOrder',
            'csrf_protection' => false
        ));
    }
    
    public function getName()
    {
        return 'order';
    }
}

<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('firstName')
            ->add('createdAt')
            ->add('email')
            ->add('phone')
            ->add('total')
            ->add('photo')
            ->add('svg')
            ->add('download')
            ->add('totalTtc')
            ->add('pm')
            ->add('plan')
            ->add('subscription')
            ->add('cycle')
            ->add('requiredUpdatePm')
            ->add('priceText')
            ->add('priceImage')
            ->add('metaData')
            ->add('isEnabled')
            ->add('secondFeePayed')
            ->add('firstFeePayed')
            ->add('nextPaymentAt')
            ->add('cycleTotal')
            ->add('granit')
            ->add('stele')
            ->add('productId')
            ->add('user')
            ->add('paymentMethod')
            ->add('method')
            ->add('status')
            ->add('gallery')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}

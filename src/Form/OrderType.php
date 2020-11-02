<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt')
            ->add('updatedAt')
            ->add('qty')
            ->add('t1')
            ->add('t2')
            ->add('addressId')
            ->add('userId')
            ->add('granitId')
            ->add('productId')
            ->add('text1', null, ['placeholder'=> 'Texte à visser personnalisé | + 25€'])
            ->add('text2', null, ['placeholder'=> 'Texte à visser personnalisé | + 25€'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}

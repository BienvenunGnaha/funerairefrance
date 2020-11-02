<?php

namespace App\Form;

use App\Entity\MoreInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class MoreInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('callAt')
            ->add('zip')
            ->add('city')
            ->add('nameCimetiere')
            ->add('emplacement')
            ->add('concession')
            ->add('acteConcession', FileType::class)
            ->add('photoConcession', FileType::class, ['multiple' => true,])
            ->add('more')
            ->add('sepulture')
            ->add('horaire')
            ->add('devis')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MoreInfo::class,
        ]);
    }
}

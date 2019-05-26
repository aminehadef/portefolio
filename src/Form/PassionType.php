<?php

namespace App\Form;

use App\Entity\Passion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PassionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('path', FileType::class,[
                'multiple' => true,
                "mapped" => false,
                'label' => 'image(s)'
            ])
            ->add('enabled',CheckboxType::class,[
                'label' => 'visible?',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Passion::class,
        ]);
    }
}

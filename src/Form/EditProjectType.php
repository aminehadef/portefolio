<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EditProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('enabled')
            ->add('path', FileType::class,[
                'multiple' => true,
                "mapped" => false,
                'label' => 'image(s)',
                'required'   => false,
            ])
            ->add('tagsText', TextType::class,[
                'required' => false,
                 'label' => 'Tags',
                 'attr' => [
                    'placeholder' => 'separate tags with comma',
                    'data-role' => "tagsinput",
                    'class' => 'inp'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}

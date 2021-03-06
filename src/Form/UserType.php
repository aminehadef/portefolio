<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "nom d'utilisateur"
            ])
            ->add('password', PasswordType::class,[
                'label' => "mot de passe"
            ])
            ->add('level', ChoiceType::class,[
                'label' => "niveau d'accès",
                'choices'  => [
                    'level 1' => 1,
                    'level 2' => 2,
                    'level 3' => 3,
                    'level 4' => 4,
                ],
            ])
            ->add('filename', FileType::class,[
                'label' => 'avatar',
                'required'   => false,
            ])
            ->add('submit', SubmitType::class,[
                'label' => "add",
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProjectType extends AbstractType
{
    /**
     * @var RouterInterface $route
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('path', FileType::class,[
                'multiple' => true,
                "mapped" => false,
                'label' => 'image(s)',
                'required' => false,
            ])
            ->add('enabled',CheckboxType::class,[
                'label' => 'visible?',
                'required' => false,


            ])
            ->add('tagsText', TextType::class,[
                'required' => false,
                 'label' => 'Tags',
                 'empty_data' => 'null',
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

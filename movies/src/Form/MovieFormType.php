<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => array(
                    'class' => 'mb-4 mt-4 bg-transparent block border 
                                border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Enter a movie title...'

                ),
                'label' => false,
                'required' => false,
            ])
            ->add('releaseYear', IntegerType::class, [
                'attr' => array(
                    'class' => 'mb-4 mt-4 bg-transparent block border 
                                border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Enter Realease Year...'

                ),
                'label' => false,
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'attr' => array(
                    'class' => 'mb-4 mt-4 bg-transparent block border 
                                border-b-2 w-full h-60 text-6xl outline-none',
                    'placeholder' => 'Enter a description...'

                ),
                'label' => false,
                'required' => false,
            ])
            ->add('imagePath', FileType::class, array(
                "required" => false,
                "mapped" => false,
                "label" => false,
                'attr' => array(
                    'class' => 'mb-4 mt-4',
                ),
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ))
            // ->add('actors')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
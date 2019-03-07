<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a username',
                        ]),
                    ],
                ])
            ->add('code', NumberType::class,
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a username',
                        ]),
                    ],
                ])
            ->add('price', TextType::class,
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a username',
                        ]),
                    ],
                ])
            ->add('description', TextareaType::class)
            ->add('availability', NumberType::class,
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a username',
                        ]),
                    ],
                ])
            ->add('status', NumberType::class,
                [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a username',
                        ]),
                    ],
                ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Product'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

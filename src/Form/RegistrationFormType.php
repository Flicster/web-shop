<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('username', TextType::class,
                [
                    'label' => 'Имя пользователя',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Пожалуйста введите имя пользователя.',
                        ]),
                    ],
                ])
            ->add('phone', TextType::class,
                [
                    'label' => 'Телефон',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Пожалуйста введите номер телефона.',
                        ]),
                        new Length([
                            'min' => 10,
                            'max' => 20,
                            'minMessage' => 'Минимальная длина номера телефона 10 цифр',
                            'maxMessage' => 'Максимальная длина номера телефона 20 цифр',
                        ]),
                    ],
                ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Пароль',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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

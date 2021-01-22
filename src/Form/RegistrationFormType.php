<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, [
                    'label' => 'Nombre',
                    'attr' => [
                        'data-msg-required' => "Debe introducir un nombre."
                    ],
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                    'attr' => [
                        'data-msg-required' => "Debe introducir un email.",
                        'data-msg-email' => "Introduce un email válido.",
                    ],
                ])
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Las contraseñas no coinciden.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options' => [
                        'label' => 'Contraseña',
                        'attr' => [
                            'data-msg-required' => "Debe introducir una constraseña."
                        ],
                    ],
                    'second_options' => [
                        'label' => 'Repite la contraseña',
                        'attr' => [
                            'data-msg-required' => "Debe repetir la contraseña.",
                            'data-rule-equalto' => "#registration_form_plainPassword_first",
                            'data-msg-equalto' => "Las contraseñas deben coincidir."
                        ],
                    ],
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Introduzca una contraseña',
                                ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'La contraseña debe tener al menos {{ limit }} caracteres.',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                                ]),
                    ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}

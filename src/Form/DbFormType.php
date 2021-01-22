<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class DbFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('system', ChoiceType::class, [
                    'label' => 'Sistema',
                    'choices' => [
                        'MySQL' => 'mysql',
                    ],
                    'required' => true,
                    'attr' => [
                        'data-msg-required' => "Debe especificar un sistema."
                    ]
                ])
                ->add('host', TextType::class, [
                    'label' => 'Host',
                    'required' => true,
                    'attr' => [
                        'data-msg-required' => "Debe especificar un host."
                    ],
                    'data' => 'localhost'
                ])
                ->add('port', NumberType::class, [
                    'label' => 'Puerto',
                    'required' => true,
                    'attr' => [
                        'data-msg-required' => "Debe especificar un puerto.",
                        'data-rule-number' => "true",
                        'data-msg-number' => "Debe ser un número.",
                    ],
                    'data' => 3303
                ])
                ->add('name', TextType::class, [
                    'label' => 'Nombre BD',
                    'required' => true,
                    'attr' => [
                        'data-msg-required' => "Debe especificar un nombre de BD."
                    ]
                ])
                ->add('user', TextType::class, [
                    'label' => 'Usuario BD',
                    'required' => true,
                    'attr' => [
                        'data-msg-required' => "Debe especificar un usuario."
                    ]
                ])
                ->add('password', PasswordType::class, [
                    'label' => 'Password BD',
                    'required' => true,
                    'attr' => [
                        'data-msg-required' => "Debe especificar una contraseña."
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([]);
    }

}

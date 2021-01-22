<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('email')
                ->add('name', TextType::class, ['label' => 'Nombre'])
                ->add('avatarFile', VichImageType::class, [
                    'required' => false,
                    'allow_delete' => false,
                    'download_uri' => false,
                    'label' => 'Imagen de perfil',
                ])
                ->add('roles', ChoiceType::class, array(
                    'label' => 'Rol',
                    'mapped' => true,
                    'expanded' => true,
                    'multiple' => true,
                    'choices' => array(
                        'ROLE_USER' => 'ROLE_USER',
                        'ROLE_ADMIN' => 'ROLE_ADMIN',
                        'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                    )
                        )
        );
        if ($options['action'] === 'new') {
            $builder
                    ->add('plainPassword', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'invalid_message' => 'Las contraseñas no coinciden.',
                        'options' => ['attr' => ['class' => 'password-field']],
                        'required' => true,
                        'first_options' => [
                            'label' => 'Contraseña',
                        ],
                        'second_options' => [
                            'label' => 'Repite la contraseña',
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
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}

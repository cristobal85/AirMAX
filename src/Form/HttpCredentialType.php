<?php

namespace App\Form;

use App\Entity\HttpCredential;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class HttpCredentialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Título'])
            ->add('username', TextType::class, ['label' => 'Usuario'])
            ->add('password', PasswordType::class, ['label' => 'Contraseña'])
            ->add('port', IntegerType::class, ['label' => 'Puerto'])
            ->add('protocol', ChoiceType::class, [
                'choices' => [
                    'HTTP'  => 'http',
                    'HTTPS' => 'https'
                ],
                'label' =>  'Protocolo'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HttpCredential::class,
        ]);
    }
}

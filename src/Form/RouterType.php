<?php

namespace App\Form;

use App\Entity\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RouterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['action'] === 'edit') {
            $builder
                    ->add('mac', TextType::class, [
                        'label' => 'Dirección MAC',
                        'attr' => [
                            'placeholder' => 'Formato XX:XX:XX:XX:XX:XX',
                            'readonly' => true
                        ]
            ]);
        } else {
            $builder
                    ->add('mac', TextType::class, [
                        'label' => 'Dirección MAC',
                        'attr' => [
                            'placeholder' => 'Formato XX:XX:XX:XX:XX:XX'
                        ]
            ]);
        }
        $builder
                ->add('active', CheckboxType::class, [
                    'label' => 'Activo',
                    'label_attr' => ['class' => 'switch-custom'],
                    'required'  => false
                ])
                ->add('address', TextType::class, [
                    'label' => 'Dirección de instalación',
                    'required' => false
                ])
                ->add('manufacturer', null, [
                    'label' => 'Fabricante',
                    'required' => true
                ])
                ->add('sshCredential', null, [
                    'label' => 'Credenciales SSH'
                ])
                ->add('httpCredential', null, [
                    'label' => 'Credenciales HTTP'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Router::class,
        ]);
    }
}

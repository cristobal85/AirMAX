<?php

namespace App\Form;

use App\Entity\Cpd;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class CpdType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, [
                    'label' => 'Nombre',
                    'attr' => [
                        'data-msg-required' => "Debe especificar un nombre."
                    ]
                ])
                ->add('latitude', NumberType::class, [
                    'label' => 'Latitud',
                    'attr' => [
                        'data-rule-number' => "true",
                        'data-msg-number' => "Debe ser un número.",
                        'data-msg-required' => "Debe especificar la latitud."
                    ]
                ])
                ->add('longitude', NumberType::class, [
                    'label' => 'Longitud',
                    'attr' => [
                        'data-rule-number' => "true",
                        'data-msg-number' => "Debe ser un número.",
                        'data-msg-required' => "Debe especificar la longitud."
                    ]
                ])
                ->add('address', TextType::class, [
                    'label' => 'Dirección',
                    'attr' => [
                        'data-msg-required' => "Debe especificar la dirección."
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Cpd::class,
        ]);
    }

}

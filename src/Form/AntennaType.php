<?php

namespace App\Form;

use App\Entity\Antenna;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AntennaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
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
                ->add('latitude', NumberType::class, [
                    'label' => 'Latitud'
                ])
                ->add('longitude', NumberType::class, [
                    'label' => 'Longitud'
                ])
                ->add('active', CheckboxType::class, [
                    'label' => 'Activa',
                    'label_attr' => ['class' => 'switch-custom'],
                    'required'  => false
                ])
                ->add('address', TextType::class, [
                    'label' => 'Dirección de instalación'
                ])
                ->add('notes', TextareaType::class, [
                    'label' => 'Notas',
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
                ->add('snmpCredential', null, [
                    'label' => 'Credenciales SNMP'
                ])
                ->add('ap', null, [
//                'required' => true,
//                'attr' => [
//                    'required' => true
//                ]
                ])
                ->add('antennaSignals', CollectionType::class, [
                    'entry_type' => AntennaSignalType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Antenna::class,
        ]);
    }

}

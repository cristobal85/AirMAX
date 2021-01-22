<?php

namespace App\Form;

use App\Entity\DhcpConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class DhcpConfigType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('host', TextType::class, [
                    'label' => 'Host',
                    'attr' => [
                        'data-msg-required' => "Debe introducir un host.",
                        'placeholder' => 'localhost'
                    ],
                    'empty_data' => 'localhost'
                ])
                ->add('username', TextType::class, [
                    'label' => 'Usuario',
                    'attr' => [
                        'data-msg-required' => "Debe introducir un usuario."
                    ],
                ])
                ->add('password', PasswordType::class, [
                    'label' => 'Password',
                    'attr' => [
                        'data-msg-required' => "Debe introducir una contraseña."
                    ],
                ])
                ->add('port', NumberType::class, [
                    'label' => 'Puerto',
                    'attr' => [
                        'data-msg-required' => "Debe introducir un puerto.",
                        'data-rule-number' => "true",
                        'data-msg-number' => "Debe ser un número.",
                        'placeholder' => 22
                    ],
                    'empty_data' => 22
                ])
                ->add('logPath', TextType::class, [
                    'label' => 'Ficher LOG',
                    'attr' => [
                        'data-msg-required' => "Debe introducir la ruta al fichero de LOG.",
                        'placeholder' => '/var/log/syslog'
                    ],
                    'empty_data' => '/var/log/syslog'
                ])
                ->add('dhcpMainFile', TextType::class, [
                    'label' => 'Fichero princial DHCP',
                    'attr' => [
                        'data-msg-required' => "Debe introducir la ruta al fichero principal.",
                        'placeholder' => '/etc/dhcp/dhcpd.conf'
                    ],
                    'empty_data' => '/etc/dhcp/dhcpd.conf'
                ])
                ->add('antennaPath', TextType::class, [
                    'label' => 'Fichero CPEs',
                    'attr' => [
                        'data-msg-required' => "Debe introducir la ruta al fichero de CPEs.",
                        'placeholder' => '/etc/dhcp/include/cpes.conf'
                    ],
                    'empty_data' => '/etc/dhcp/include/cpes.conf'
                ])
                ->add('dhcpInitScript', TextType::class, [
                    'label' => 'Script de servicio DHCP',
                    'attr' => [
                        'data-msg-required' => "Debe introducir la ruta al script del servicio.",
                        'placeholder' => 'sudo /etc/init.d/isc-dhcp-server'
                    ],
                    'empty_data' => 'sudo /etc/init.d/isc-dhcp-server',
                    'help' => 'Asegúrese de incluir el comando sudo si es necesario.',
                ])
                ->add('antennaSubclass', TextType::class, [
                    'label' => 'Subclass DHCP para CPEs',
                    'attr' => [
                        'data-msg-required' => "Debe introducir un nombre de Subclass.",
                        'placeholder' => 'CPE01'
                    ],
                    'empty_data' => 'CPE01'
                ])
                ->add('ataPath', TextType::class, [
                    'label' => 'Fichero ATAs',
                    'required' => false
                ])
                ->add('ataSubclass', TextType::class, [
                    'label' => 'Subclass DHCP para ATAs',
                    'required' => false
                ])
                ->add('routerPath', TextType::class, [
                    'label' => 'Fichero Routers',
                    'required' => false,
                ])
                ->add('routerSubclass', TextType::class, [
                    'label' => 'Subclass DHCP para ROUTERs',
                    'required' => false
                ])
                ->add('staticPath', TextType::class, [
                    'label' => 'Fichero IPs estáticas',
                    'attr' => [
                        'data-msg-required' => "Debe introducir la ruta al fichero de IPs estáticas.",
                        'placeholder' => '/etc/dhcp/include/statics.conf'
                    ],
                    'empty_data' => '/etc/dhcp/include/statics.conf'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => DhcpConfig::class,
        ]);
    }

}

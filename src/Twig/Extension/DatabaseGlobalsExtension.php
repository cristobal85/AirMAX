<?php

namespace App\Twig\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class DatabaseGlobalsExtension extends AbstractExtension implements GlobalsInterface
{

   protected $em;

   public function __construct(EntityManagerInterface $em)
   {
      $this->em = $em;
   }

   public function getGlobals(): array
   {
      return [
          'DHCP_CONFIG' => $this->em->getRepository(\App\Entity\DhcpConfig::class)->findOneBy(array(), array('id' => 'ASC'), 1),
      ];
   }
}

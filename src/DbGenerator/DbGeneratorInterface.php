<?php

namespace App\DbGenerator;

use App\Entity\DhcpConfig;
use App\DbGenerator\Exception\DbGeneratorExceptionInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
interface DbGeneratorInterface {
    
    /**
     * @param DhcpConfig $dhcpConfig
     * @param EntityManagerInterface $em
     * @throws DbGeneratorExceptionInterface
     */
    public function generate(DhcpConfig $dhcpConfig, EntityManagerInterface $em);
}

<?php

namespace App\DbGenerator;

use \App\Entity\DhcpConfig;
use Symfony\Component\String\UnicodeString;
use App\Entity\Antenna;
use App\Entity\Client;
use App\DbGenerator\Exception\DbAntennaGeneratorException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class DbAntennaGenerator extends DbGenerator implements DbGeneratorInterface {

    /**
     * @param DhcpConfig $dhcpConfig
     * @param EntityManagerInterface $em
     * @throws DbClientGeneratorException
     */
    public function generate(DhcpConfig $dhcpConfig, EntityManagerInterface $em) {
        $antennaFile = $this->sshAdapter->exec('cat ' . $dhcpConfig->getAntennaPath());
        $clientRep = $em->getRepository(Client::class);
        $antennaRep = $em->getRepository(Antenna::class);
        $cpes = explode(PHP_EOL, $antennaFile);
        if (empty($cpes)) {
            throw new DbClientGeneratorException("No se encontraron lineas en el fichero " . $dhcpConfig->getAntennaPath() . ".");
        }
        foreach ($cpes as $cpe) {
            $cpeStr = new UnicodeString($cpe);
            $cpeFromFile = $cpeStr->match('/.*(([0-9A-Fa-f]{2}[:]){5}([0-9A-Fa-f]{2})); # .* - (\d+) - .* - (.*)$/');
            if (!empty($cpeFromFile)) { // ¿BLANK LINE?
                $mac = trim($cpeFromFile[1]);
                if (!$antennaRep->findOneBy(['mac' => $mac])) {
                    
                    $client = $clientRep->findOneBy(['code' => (int) trim($cpeFromFile[4])]);

                    if ($client) {
                        $antenna = new Antenna();
                        $antenna
                                ->setClient($client)
                                ->setMac(trim($cpeFromFile[1]))
                                ->setAddress(trim($cpeFromFile[5]))
                        ;

                        $errors = $this->validator->validate($antenna);
                        if (count($errors) > 0) {
                            $errorsString = (string) $errors;
                            throw new DbAntennaGeneratorException($errorsString . 'MAC: ' . $antenna->getMac());
                        }
                    }

                    $em->persist($antenna);
                    $em->flush();
                }
            }
        }
    }

}

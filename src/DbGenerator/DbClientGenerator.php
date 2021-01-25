<?php

namespace App\DbGenerator;

use \App\Entity\DhcpConfig;
use Symfony\Component\String\UnicodeString;
use App\Entity\Client;
use App\DbGenerator\Exception\DbClientGeneratorException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class DbClientGenerator extends DbGenerator implements DbGeneratorInterface {

    /**
     * @param DhcpConfig $dhcpConfig
     * @param EntityManagerInterface $em
     * @throws DbClientGeneratorException
     */
    public function generate(DhcpConfig $dhcpConfig, EntityManagerInterface $em) {
        $clientRep = $em->getRepository(Client::class);
        $antennaFile = $this->sshAdapter->exec('cat ' . $dhcpConfig->getAntennaPath());
        $cpes = explode(PHP_EOL, $antennaFile);
        if (empty($cpes)) {
            throw new DbClientGeneratorException("No se encontraron lineas en el fichero " . $dhcpConfig->getAntennaPath() . ".");
        }
        foreach ($cpes as $cpe) {
            $cpeStr = new UnicodeString($cpe);
            $clientFromFile = $cpeStr->match('/.*- (\d+) - ([^⁻]+)-(.*)$/');
            if (!empty($clientFromFile)) {  // ¿BLANK LINE?
                $clientCode = (int) $clientFromFile[1];
                if (!$clientRep->findOneBy(['code' => $clientCode])) {
                    $client = new Client();
                    $client
                            ->setCode((int) $clientFromFile[1])
                            ->setName($clientFromFile[2])
                            ->setAdress($clientFromFile[3])
                    ;

                    $errors = $this->validator->validate($client);
                    if (count($errors) > 0) {
                        $errorsString = (string) $errors;
                        throw new DbClientGeneratorException($errorsString . 'Código de cliente: ' . (string) $client->getCode());
                    }

                    $em->persist($client);
                    $em->flush();
                    // TODO: catch exception
                }
            }
        }
         
    }

}

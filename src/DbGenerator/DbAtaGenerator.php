<?php

namespace App\DbGenerator;

use \App\Entity\DhcpConfig;
use Symfony\Component\String\UnicodeString;
use App\Entity\Ata;
use App\Entity\Client;
use App\DbGenerator\Exception\DbAtaGeneratorException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class DbAtaGenerator extends DbGenerator implements DbGeneratorInterface {

    /**
     * @param DhcpConfig $dhcpConfig
     * @param EntityManagerInterface $em
     * @throws DbClientGeneratorException
     */
    public function generate(DhcpConfig $dhcpConfig, EntityManagerInterface $em) {
        if (!$dhcpConfig->getAtaPath()) {
            return;
        }
        $clientRep = $em->getRepository(Client::class);
        $ataRep = $em->getRepository(Ata::class);
        ;
        $ataFile = $this->sshAdapter->exec('cat ' . $dhcpConfig->getAtaPath());
        $atas = explode(PHP_EOL, $ataFile);
        if (empty($atas)) {
            throw new DbAtaGeneratorException("No se encontraron lineas en el fichero " . $dhcpConfig->getAtaPath() . ".");
        }
        foreach ($atas as $ata) {
            $ataStr = new UnicodeString($ata);
            $ataFromFile = $ataStr->match('/.*(([0-9A-Fa-f]{2}[:]){5}([0-9A-Fa-f]{2})); # (\d+) - (\d+) - .* - (.*)$/');
            if (!empty($ataFromFile)) { // ¿BLANK LINE o comment?
                $mac = trim($cpeFromFile[1]);
                if (!$ataRep->findOneBy(['mac' => $mac])) {
                    $client = $clientRep->findOneBy(['code' => (int) trim($ataFromFile[4])]);
                    if ($client) {
                        $ataModel = new Ata();
                        $ataModel
                                ->setClient($client)
                                ->setMac(trim($ataFromFile[1]))
                                ->setPhone(trim($ataFromFile[5]))
                                ->setAddress(trim($ataFromFile[6]))
                        ;

                        $errors = $this->validator->validate($ataModel);
                        if (count($errors) > 0) {
                            $errorsString = (string) $errors;
                            throw new DbAtaGeneratorException($errorsString . 'MAC: ' . $ataModel->getMac());
                        }

                        $em->persist($ataModel);
                        $em->flush();
                    }
                }
            }
        }
    }

}

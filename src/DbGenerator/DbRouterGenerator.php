<?php

namespace App\DbGenerator;

use \App\Entity\DhcpConfig;
use Symfony\Component\String\UnicodeString;
use App\Entity\Router;
use App\Entity\Client;
use App\DbGenerator\Exception\DbRouterGeneratorException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class DbRouterGenerator extends DbGenerator implements DbGeneratorInterface {

    /**
     * @param DhcpConfig $dhcpConfig
     * @param EntityManagerInterface $em
     * @throws DbClientGeneratorException
     */
    public function generate(DhcpConfig $dhcpConfig, EntityManagerInterface $em) {
        if (!$dhcpConfig->getRouterPath()) {
            return;
        }
        $clientRep = $em->getRepository(Client::class);
        $routerRep = $em->getRepository(Router::class);
        $routerFile = $this->sshAdapter->exec('cat ' . $dhcpConfig->getRouterPath());
        $routers = explode(PHP_EOL, $routerFile);
        if (empty($routers)) {
            throw new DbRouterGeneratorException("No se encontraron lineas en el fichero " . $dhcpConfig->getRouterPath() . ".");
        }
        foreach ($routers as $router) {
            $routerStr = new UnicodeString($router);
            $routerFromFile = $routerStr->match('/.*(([0-9A-Fa-f]{2}[:]){5}([0-9A-Fa-f]{2})); # (\d+) - .* - (.*)$/');
            if (!empty($routerFromFile)) { // ¿BLANK LINE o comment?
                $mac = (int) $cpeFromFile[1];
                if (!$routerRep->findOneBy(['mac' => $mac])) {
                    $client = $clientRep->findOneBy(['code' => trim($routerFromFile[4])]);
                    if ($client) {
                        $routerModel = new Router();
                        $routerModel
                                ->setClient($client)
                                ->setMac(trim($routerFromFile[1]))
                                ->setAddress(trim($routerFromFile[5]))
                        ;

                        $errors = $this->validator->validate($routerModel);
                        if (count($errors) > 0) {
                            $errorsString = (string) $errors;
                            throw new DbRouterGeneratorException($errorsString . 'MAC: ' . $routerModel->getMac());
                        }

                        $em->persist($routerModel);
                        $em->flush();
                    }
                }
            }
        }
    }

}

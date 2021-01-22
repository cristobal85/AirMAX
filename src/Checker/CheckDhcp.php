<?php

namespace App\Checker;

use Symfony\Component\Form\FormInterface;
use phpseclib\Net\SSH2;
use App\Checker\Utils\CheckSshPath;
use \App\Checker\CheckStatus;

/**
 * Description of CheckDhcp
 *
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class CheckDhcp implements Checkeable {

    /**
     * @param FormInterface $dhcpForm
     * @return CheckStatus
     */
    public function check(FormInterface $dhcpForm): CheckStatus {
        $dhcpHost = $dhcpForm->get('host')->getData();
        $dhcpPort = $dhcpForm->get('port')->getData();
        $dhcpUser = $dhcpForm->get('username')->getData();
        $dhcpPass = $dhcpForm->get('password')->getData();
        $dhcpFiles = [];
        $dhcpFiles[] = ['filePath' => $dhcpForm->get('dhcpMainFile')->getData(), 'required' => true, 'permission' => CheckSshPath::WRITEABLE];
        $dhcpFiles[] = ['filePath' => $dhcpForm->get('staticPath')->getData(), 'required' => true, 'permission' => CheckSshPath::WRITEABLE];
        $dhcpFiles[] = ['filePath' => $dhcpForm->get('antennaPath')->getData(), 'required' => true, 'permission' => CheckSshPath::WRITEABLE];
        $dhcpFiles[] = ['filePath' => $dhcpForm->get('ataPath')->getData(), 'required' => false, 'permission' => CheckSshPath::WRITEABLE];
        $dhcpFiles[] = ['filePath' => $dhcpForm->get('routerPath')->getData(), 'required' => false, 'permission' => CheckSshPath::WRITEABLE];
        $dhcpFiles[] = ['filePath' => $dhcpForm->get('logPath')->getData(), 'required' => true, 'permission' => CheckSshPath::READABLE];
        try {
            $ssh = new SSH2($dhcpHost, $dhcpPort);
            if (!$ssh->login($dhcpUser, $dhcpPass)) {
                return new CheckStatus(CheckStatus::ERROR, "Error al conectar con el servidor DHCP: Usuario o contraseña invalidos.");
            }

            $checkSshPath = new CheckSshPath($ssh);
            foreach ($dhcpFiles as $dhcpFile) {
                switch ($dhcpFile['permission']) {
                    case CheckSshPath::READABLE:
                        if (!$checkSshPath->isReadable($dhcpFile['filePath'], $dhcpFile['required'])) {
                            return new CheckStatus(CheckStatus::ERROR, "Fichero (" . $dhcpFile['filePath'] . "): No tiene permisos de lectura. ");
                        }
                        break;
                    case CheckSshPath::WRITEABLE:
                        if (!$checkSshPath->isWriteable($dhcpFile['filePath'], $dhcpFile['required'])) {
                            return new CheckStatus(CheckStatus::ERROR, "Fichero (" . $dhcpFile['filePath'] . "): No tiene permisos de escritura. ");
                        }
                        break;
                }
            }
        } catch (\Exception $e) {
            return new CheckStatus(CheckStatus::ERROR, "Error al conectar con el servidor DHCP: " . $e->getMessage());
        }

        return new CheckStatus(CheckStatus::OK, 'Se conectó al servidor DHCP correctamente.');
    }

}

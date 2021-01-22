<?php

namespace App\Ssh\Command\Dhcp;

use App\Ssh\Command\SshCommandInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
final class SshDhcpRegisterAntenna extends SshDhcpCommand implements SshCommandInterface {

    public function execute(): string {
        $cmd = 'echo \'' .
                'subclass "'. $this->dhcpConfig->getAntennaSubclass().'" 1:' . $this->device->getMac() . '; '
                . '# ' . $this->device->getAp() .
                ' - ' . $this->device->getClient()->getCode() .
                ' - ' . $this->device->getClient()->getName() .
                ' - ' . $this->device->getAddress() . '\'' .
                ' >> ' . $this->dhcpConfig->getAntennaPath();
        return $this->sshAdapter->exec($cmd);
    }

    public function undo(): string {
        $cmd = "sed -i.bak '/". $this->device->getMac(). "/d' ". $this->dhcpConfig->getAntennaPath();
        return $this->sshAdapter->exec($cmd);
    }

}

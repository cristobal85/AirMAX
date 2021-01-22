<?php

namespace App\Ssh\Command\Dhcp;

use App\Ssh\Command\SshCommandInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
final class SshDhcpUnregisterAta extends SshDhcpCommand implements SshCommandInterface {

    public function execute(): string {
        $cmd = "sed -i.bak '/". $this->device->getMac(). "/d' ". $this->dhcpConfig->getAtaPath();
        return $this->sshAdapter->exec($cmd);
    }

    public function undo(): string {
        $cmd = 'echo \'' .
                'subclass "'. $this->dhcpConfig->getAtaSubclass().'" 1:' . $this->device->getMac() . '; '
                . '# ' . $this->device->getClient()->getCode() .
                ' - ' . $this->device->getClient()->getName() .
                ' - ' . $this->device->getAddress() . '\'' .
                ' >> ' . $this->dhcpConfig->getAtaPath();
        return $this->sshAdapter->exec($cmd);
    }

}

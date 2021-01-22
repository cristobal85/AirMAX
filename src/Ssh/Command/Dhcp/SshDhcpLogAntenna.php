<?php

namespace App\Ssh\Command\Dhcp;

use App\Ssh\Command\SshCommandInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
final class SshDhcpLogAntenna extends SshDhcpCommand implements SshCommandInterface {
    
    public const MAX_LINES = 20;

    public function execute(): string {
        $cmd = 'tac '. $this->dhcpConfig->getLogPath().
                ' | grep -iE ".*'. $this->device->getMac() . '.*" | head -'. self::MAX_LINES;
        return $this->sshAdapter->exec($cmd);
    }

    public function undo(): string {
        return "";
    }

}

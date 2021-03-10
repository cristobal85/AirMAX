<?php

namespace App\Ssh\Command\Dhcp;

use App\Ssh\Command\SshCommandInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
final class SshDhcpGetIp extends SshDhcpCommand implements SshCommandInterface {
    
    public const MAX_LINES = 1;

    public function execute(): string {
        $cmd = 'tac '. $this->dhcpConfig->getLogPath().
                ' | grep -iE ".*dhcpack.*'. $this->device->getMac() . '.*" | head -'. self::MAX_LINES. ' | grep -ioE "[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3} "';
        return trim($this->sshAdapter->exec($cmd));
    }

    public function undo(): string {
        return "";
    }

}

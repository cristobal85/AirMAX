<?php

namespace App\Ssh\Command\Dhcp;

use App\Ssh\Command\SshCommandInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
final class SshDhcpLogSystem extends SshDhcpCommand implements SshCommandInterface {
    
    public const MAX_LINES = 50;

    public function execute(): string {
        $cmd = 'tac '. $this->dhcpConfig->getLogPath().
                ' | grep -iE ".*([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2}).*" | head -'. self::MAX_LINES;
        return $this->sshAdapter->exec($cmd);
    }

    public function undo(): string {
        return "";
    }

}

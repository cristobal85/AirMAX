<?php

namespace App\Ssh\Command\Dhcp;

use App\Ssh\Command\SshCommandInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
final class SshDhcpScriptStatusCommand extends SshDhcpCommand implements SshCommandInterface {
    
    
    public function execute(): string {
        return $this->sshAdapter->exec($this->dhcpConfig->getDhcpInitScript(). ' status');
    }

    public function undo(): string {
        return $this->sshAdapter->exec($this->dhcpConfig->getDhcpInitScript(). ' status');
    }

}

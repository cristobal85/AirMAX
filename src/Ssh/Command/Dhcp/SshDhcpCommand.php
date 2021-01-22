<?php

namespace App\Ssh\Command\Dhcp;

use App\Ssh\Command\SshCommand;
use App\Entity\DhcpConfig;
use App\Ssh\Adapter\SshAdapterInterface;
use App\Entity\DeviceEntityInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class SshDhcpCommand extends SshCommand {
    
    /**
     * @var DhcpConfig
     */
    protected $dhcpConfig;
    
    /**
     * @var DeviceEntityInterface
     */
    protected $device;
    
    public function __construct(
            SshAdapterInterface $sshAdapter, 
            DhcpConfig $dhcpConfig,
            DeviceEntityInterface $device = null) {
        parent::__construct($sshAdapter);
        $this->dhcpConfig = $dhcpConfig;
        $this->device = $device;
    }
}

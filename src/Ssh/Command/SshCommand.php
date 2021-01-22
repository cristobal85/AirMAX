<?php

namespace App\Ssh\Command;

use App\Ssh\Adapter\SshAdapterInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class SshCommand {
    
    /**
     * @var SshAdapterInterface
     */
    protected $sshAdapter;
    
    public function __construct(SshAdapterInterface $sshAdapter) {
        $this->sshAdapter = $sshAdapter;
    }
    
}

<?php

namespace App\Ssh\Command;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
interface SshCommandInterface {
    
    /**
     * @return string Output of command
     * @throws \App\Ssh\Exception\SshExceptionInterface
     */
    public function execute():string;
    
    /**
     * @return string Ouput of undo command
     * @throws \App\Ssh\Exception\SshExceptionInterface
     */
    public function undo(): string;
    
}

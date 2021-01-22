<?php


namespace App\Ssh\Adapter;

use \App\Ssh\Exception\SshExceptionInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
interface SshAdapterInterface {
    
    public const LINUX_COMMAND_STATUS_SUCCESS = 0;
    
    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login(string $username, string $password): bool;
    
    /**
     * @param string $command
     * @return string
     * @throws SshExceptionInterface
     */
    public function exec(string $command): string;
}

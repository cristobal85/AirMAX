<?php


namespace App\Ssh\Adapter;

use phpseclib\Net\SSH2;
use App\Ssh\Exception\SshExceptionInterface;
use App\Ssh\Exception\SshExecException;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class SshAdapter implements SshAdapterInterface {
    
    private $ssh;
    
    public function __construct(string $host, int $port) {
        $this->ssh = new SSH2($host, $port);
    }

    /**
     * @param string $command
     * @return string
     * @throws SshExceptionInterface
     */
    public function exec(string $command): string {
        $output = $this->ssh->exec($command);
        $status = $this->ssh->getExitStatus();
        if ($status != SshAdapterInterface::LINUX_COMMAND_STATUS_SUCCESS) {
            throw new SshExecException($output);
        }
        return $output;
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function login(string $username, string $password): bool {
        return $this->ssh->login($username, $password);
    }

}

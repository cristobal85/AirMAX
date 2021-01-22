<?php


namespace App\ShellCommand;

use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
interface ShellCommand {
    
    /**
     * @return bool
     */
    public function execute() : bool;
    
    /**
     * @param array $inputArgs
     * @return string Return the command shell output
     * @throws ProcessFailedException
     */
    public function run(array $inputArgs = []) : string;
}

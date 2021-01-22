<?php

namespace App\ShellCommand\Install;

use App\ShellCommand\ShellCommand;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\ShellCommand\Enum\Timeout;

/**
 * This command generate the original schema in the database.
 *
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class ShellGenerateSchemaCommand implements ShellCommand {

    /**
     * @var string
     */
    private $commandFile;

    public function __construct(KernelInterface $kernel) {
        $this->commandFile = $kernel->getProjectDir() . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'console';
    }

    /**
     * @return bool
     */
    public function execute(): bool {
        $process = new Process([$this->commandFile, "doctrine:schema:update", "--force"]);
        $process->setTimeout(Timeout::PROCESS_TIMEOUT);
        $process->run();

        return $process->isSuccessful();
    }

    /**
     * @param array $inputArgs
     * @return string
     * @throws ProcessFailedException
     */
    public function run(array $inputArgs = []): string {
        $command = [$this->commandFile, "doctrine:schema:update", "--force"];
        $input = array_merge($command, $inputArgs);
        
        $process = new Process($input);
        $process->setTimeout(Timeout::PROCESS_TIMEOUT);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

}

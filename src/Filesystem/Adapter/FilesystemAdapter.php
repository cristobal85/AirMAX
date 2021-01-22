<?php


namespace App\Filesystem\Adapter;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Filesystem\Enum\FilePath;
use App\Filesystem\Exception\DirPermissionsException;
use Symfony\Component\Form\FormInterface;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class FilesystemAdapter {
    
    /**
     *
     * @var Filesystem
     */
    private $fs;
    
    /**
     * @var string
     */
    private $projectDir;
    
    public function __construct(KernelInterface $kernel) {
        $this->fs = new Filesystem();
        $this->projectDir = $kernel->getProjectDir();
    }

    /**
     * @param string $directory
     * @return bool
     */
    public function isDirWriteable(string $directory) : bool {
        return is_writable($directory);
    }

    /**
     * @return void
     * @throws DirPermissionsException Throw Exceptio if directory is not writeable.
     */
    public function checkProjectDirectory() : void {
        if ( !$this->isDirWriteable($this->projectDir) ) {
            throw new DirPermissionsException('No tiene permisos de escritura en directorio '. $this->projectDir);
        }
    }
    
    /**
     * @param string $filePath
     * @return void
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function touch(string $filePath) : void {
        $this->fs->touch($filePath);
    }
    
    /**
     * @param string $filePath
     * @return void
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function remove(string $filePath) : void {
        $this->fs->remove($filePath);
    }
    
    /**
     * @return void
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function touchEnvLocalFile() : void {
        $this->remove($this->projectDir. DIRECTORY_SEPARATOR. FilePath::ENV_LOCAL_FILE);
        $this->touch($this->projectDir. DIRECTORY_SEPARATOR. FilePath::ENV_LOCAL_FILE);
    }
    
    /**
     * @return void
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function writeDbConfigInEnvLocalFile(FormInterface $dbForm) {
        $envLocalFile = $this->projectDir. DIRECTORY_SEPARATOR. FilePath::ENV_LOCAL_FILE;
        $this->fs->appendToFile($envLocalFile, "DATABASE_URL=mysql://". 
                $dbForm->get('user')->getData(). ":". 
                $dbForm->get('password')->getData(). "@". $dbForm->get('host')->getData(). 
                ":". $dbForm->get('port')->getData(). 
                "/". $dbForm->get('name')->getData(). PHP_EOL);
    }
    
    /**
     * @return void
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function writeCpdConfigInEnvLocalFile(FormInterface $cpdForm) {
        $envLocalFile = $this->projectDir. DIRECTORY_SEPARATOR. FilePath::ENV_LOCAL_FILE;
        $this->fs->appendToFile($envLocalFile, "APP_COMPANY=\"". $cpdForm->get('name')->getData(). "\"". PHP_EOL);
    }
    
    /**
     * @return void
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function writeInstalledInEnvLocalFile(bool $installed) {
        $envLocalFile = $this->projectDir. DIRECTORY_SEPARATOR. FilePath::ENV_LOCAL_FILE;
        $this->fs->appendToFile($envLocalFile, "APP_INSTALLED=". (($installed) ? 'true' : 'false'). PHP_EOL);
    }
}

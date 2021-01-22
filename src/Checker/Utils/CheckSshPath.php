<?php

namespace App\Checker\Utils;

use phpseclib\Net\SSH2;

/**
 * @author Cristóbal Cobos <cristobal.cobos@intnova.com>
 */
class CheckSshPath {

    /**
     * @var SSH2
     */
    private $conn;
    
    public const READABLE = 'readable';
    
    public const WRITEABLE = 'writeable';
    
    public function __construct(SSH2 $conn) {
        $this->conn = $conn;
    }

    /**
     * @param type $file
     * @return bool
     */
    public function isReadable(?string $file, bool $required = false): bool {
        if (!$file && $required) {
            return false;
        }
        if ($file) {
            $fileFileIsReadable = (boolean) $this->conn->exec('if [ -r "' . $file . '" ]; then echo "true"; fi');
            return $fileFileIsReadable;
        }
        return true;
    }
    
    /**
     * @param type $file
     * @return bool
     */
    public function isWriteable(?string $file, bool $required = false): bool {
        if (!$file && $required) {
            return false;
        }
        if ($file) {
            $fileFileIsWriteable = (boolean) $this->conn->exec('if [ -w "' . $file . '" ]; then echo "true"; fi');
            return $fileFileIsWriteable;
        }
        return true;
    }

}

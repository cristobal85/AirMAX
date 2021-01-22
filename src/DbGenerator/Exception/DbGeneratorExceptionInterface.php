<?php


namespace App\DbGenerator\Exception;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
interface DbGeneratorExceptionInterface extends \Throwable {
    
    /**
     * @return string
     */
    public function getReason(): string;
}

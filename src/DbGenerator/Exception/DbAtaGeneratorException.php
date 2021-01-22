<?php


namespace App\DbGenerator\Exception;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class DbAtaGeneratorException extends \Exception implements DbGeneratorExceptionInterface {
    
    public function getReason(): string {
        return "Hubo un error al obtener los datos del ATA.";
    }

}

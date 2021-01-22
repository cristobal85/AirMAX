<?php

namespace App\Checker;

use Symfony\Component\Form\FormInterface;
use \App\Checker\CheckStatus;

/**
 * @author Cristóbal <cristobal.cobos@intnova.com>
 */
class CheckDB implements Checkeable {

    public function check(FormInterface $dbForm) : CheckStatus {
        
        $dbHost = $dbForm->get('host')->getData();
        $dbPort = $dbForm->get('port')->getData();
        $dbName = $dbForm->get('name')->getData();
        $dbUser = $dbForm->get('user')->getData();
        $dbPass = $dbForm->get('password')->getData();
        try {
            $connection = new \PDO(
                    "mysql:host=$dbHost;port=$dbPort;dbname=$dbName",
                    $dbUser,
                    $dbPass);
        } catch (\PDOException $e) {
            return new CheckStatus(CheckStatus::ERROR, "Error al conectar con la base de datos: " . $e->getMessage());
        }
        
        return new CheckStatus(CheckStatus::OK, 'Se conectó a la base de datos correctamente.');
    }

}

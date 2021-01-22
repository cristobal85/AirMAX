<?php

namespace App\Entity;

use App\Entity\Ap;
use App\Entity\Client;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
interface DeviceEntityInterface {

    /**
     * @return string
     */
    public function getMac(): ?string;

    /**
     * @return Client
     */
    public function getClient(): ?Client;

    /**
     * @return string
     */
    public function getAddress(): ?string;
    
}

<?php

namespace App\Checker;

/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class CheckStatus {
    
    /**
     * @var boolean 
     */
    private $status;

    /**
     * @var string
     */
    private $message;
    
    public const OK = true;
    
    public const ERROR = false;
    
    public function __construct(bool $status, string $message) {
        $this->status = $status;
        $this->message = $message;
    }
    
    public function isOk(): bool {
        return $this->status;
    }
    
    public function __toString() : string {
        return $this->message;
    }
}

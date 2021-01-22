<?php


namespace App\DbGenerator;

use App\Ssh\Adapter\SshAdapterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
class DbGenerator {
    
    /**
     * @var SshAdapterInterface
     */
    protected $sshAdapter;
    
    /**
     *
     * @var ValidatorInterface
     */
    protected $validator;
    
    /**
     * @param SshAdapterInterface $sshAdapter
     * @param ValidatorInterface $validator
     */
    public function __construct(SshAdapterInterface $sshAdapter, ValidatorInterface $validator) {
        $this->sshAdapter = $sshAdapter;
        $this->validator = $validator;
    }

}

<?php

namespace App\Checker;

use Symfony\Component\Form\FormInterface;
use \App\Checker\CheckStatus;

/**
 *
 * @author Cristóbal Cobos Budia <cristobal.cobos@intnova.com>
 */
interface Checkeable {
    
    public function check(FormInterface $form) : CheckStatus;
}

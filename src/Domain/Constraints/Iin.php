<?php

namespace ZnKaz\Base\Domain\Constraints;

use Symfony\Component\Validator\Constraint;

class Iin extends Constraint
{

    public $message = 'IIN "{{ string }}" not found';
}

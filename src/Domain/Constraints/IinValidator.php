<?php

namespace ZnKaz\Base\Domain\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnKaz\Base\Domain\Helpers\IinParser;

class IinValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Iin) {
            throw new UnexpectedTypeException($constraint, Iin::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        try {
            $iinEntity = IinParser::parse($value);
        } catch (UnprocessibleEntityException $e) {
            if($e->getMessage()) {
                $message = $e->getMessage();
            } else {
                $message = $constraint->message;
            }
            $this->context->buildViolation($message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}

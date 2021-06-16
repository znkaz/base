<?php

namespace ZnKaz\Base\Domain\Libs;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnKaz\Base\Domain\Entities\CheckSumEntity;

class Validator
{

    public function validate(string $value): void
    {
        $this->validateValue($value);
        $sequence = $this->validateCheckSum($value);
    }

    private function validateValue(string $value): void
    {
        $violationList = ValidationHelper::validateValue($value, [
            new NotBlank(),
            new Length(['value' => 12]),
            new Regex(['pattern' => '/^\d+$/'])
        ]);
        if ($violationList->count()) {
            $unprocessibleEntityException = new UnprocessibleEntityException();
            foreach ($violationList as $validateErrorEntity) {
                //$validateErrorEntity->setField('value');
            }
            $unprocessibleEntityException->setErrorCollection(ValidationHelper::createErrorCollectionFromViolationList($violationList));
            throw $unprocessibleEntityException;
        }
    }

    private function validateCheckSum(string $value): array
    {
        $sumActual = intval(substr($value, 11, 1));
        $checkSum = new CheckSum();
        $checkSumEntity = $checkSum->generateSum($value);
        $sumCalculated = $checkSumEntity->getSum();
        if ($sumActual != $sumCalculated) {
            $unprocessibleEntityException = new UnprocessibleEntityException();
            $unprocessibleEntityException->add('value', 'Bad check sum! Actual: ' . $sumActual . ', expected: ' . $sumCalculated);
            throw $unprocessibleEntityException;
        }
        return $checkSumEntity->getSequence();
    }
}

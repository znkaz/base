<?php

namespace ZnKaz\Base\Domain\Helpers;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnKaz\Base\Domain\Entities\IinEntity;
use ZnKaz\Base\Domain\Entities\IndividualEntity;
use ZnKaz\Base\Domain\Entities\JuridicalEntity;
use ZnKaz\Base\Domain\Enums\TypeEnum;

class IinParser
{

    public static function parse($value): IinEntity
    {
        self::validateValue($value);
        $iinEntity = new IinEntity();
        $iinEntity->setValue($value);
        $iinEntity->setType(self::getType($value));
        $iinEntity->setSerialNumber(substr($value, 7, 4));
        $iinEntity->setCheckSum(substr($value, 11, 1));
        $iinEntity->setCentury(substr($value, 6, 1));
        self::validateCentury($iinEntity->getCentury());

        try {
            $birthday = IinDateHelper::parseDate($iinEntity);
        } catch (\Exception $e) {
            $unprocessibleEntityException = new UnprocessibleEntityException();
            $unprocessibleEntityException->add('birthday', $e->getMessage());
            throw $unprocessibleEntityException;
        }

        if ($iinEntity->getType() == TypeEnum::INDIVIDUAL) {
            //$iinEntity->setSex(self::getSex($iinEntity));
            $individualEntity = new IndividualEntity();
            $individualEntity->setSex(self::getSex($iinEntity));
            $individualEntity->setBirthday($birthday);
            $iinEntity->setIndividual($individualEntity);
        } elseif ($iinEntity->getType() == TypeEnum::JURIDICAL) {
            $juridicalEntity = new JuridicalEntity();
            $juridicalEntity->setType($value[4]);
            $juridicalEntity->setPart($value[5]);
            $juridicalEntity->setRegistrationDate($birthday);
            $iinEntity->setJuridical($juridicalEntity);
        }

        self::validateSum($value);
        return $iinEntity;
    }

    private static function getType($value): string
    {
        $typeMarker = $value[4];
        if (in_array($typeMarker, [0, 1, 2, 3])) {
            return TypeEnum::INDIVIDUAL;
        }
        if (in_array($typeMarker, [4, 5, 6])) {
            return TypeEnum::JURIDICAL;
        }
        throw new Exception('Error ten day');
    }

    private static function validateCentury($century): void
    {
        $maxCentury = self::getMaxCentury();
        if ($century < 0 || $century > $maxCentury) {
            throw new Exception('Century not correct');
        }
    }

    private static function getMaxCentury(): int
    {
        $nowYear = date('Y');
        $nowEpoch = substr($nowYear, 0, 2);
        return ($nowEpoch - 18 + 1) * 2;
    }

    private static function validateValue(string $value): void
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

    private static function validateSum(string $value): void
    {
        $sum = intval(substr($value, 11, 1));
        $sumCalculated = CheckSum::generateSum($value);
        if ($sum != $sumCalculated) {
            $unprocessibleEntityException = new UnprocessibleEntityException();
            $unprocessibleEntityException->add('value', 'Bad check sum');
            throw $unprocessibleEntityException;
        }
    }

    private static function getSex(IinEntity $iinEntity)
    {
        $century = $iinEntity->getCentury();
        return !empty($century % 2) ? 'male' : 'female';
    }
}

<?php

namespace ZnKaz\Base\Domain\Helpers;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnCore\Domain\Helpers\ValidationHelper;
use ZnKaz\Base\Domain\Entities\IinEntity;

class IinParser
{

    public static function parse($value): IinEntity
    {
        self::validateValue($value);
        $iinEntity = new IinEntity();
        $iinEntity->setValue($value);
        $iinEntity->setSerialNumber(substr($value, 7, 4));
        $iinEntity->setCheckSum(substr($value, 11, 1));
        $iinEntity->setCentury(substr($value, 6, 1));
        self::validateCentury($iinEntity->getCentury());
        $iinEntity->setSex(self::getSex($iinEntity));
        try {
            $iinEntity->setBirthday(IinDateHelper::parseDate($iinEntity));
        } catch (\Exception $e) {
            $unprocessibleEntityException = new UnprocessibleEntityException();
            $unprocessibleEntityException->add('birthday', $e->getMessage());
            throw $unprocessibleEntityException;
        }
        self::validateSum($value);
        return $iinEntity;
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
        $nowAge = substr($nowYear, 0, 2);
        return ($nowAge - 18 + 1) * 2;
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
        $sumCalculated = self::generateSum($value);
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

    private static function generateSum($inn): int
    {
        $multiplication =
            1 * $inn[0] +
            2 * $inn[1] +
            3 * $inn[2] +
            4 * $inn[3] +
            5 * $inn[4] +
            6 * $inn[5] +
            7 * $inn[6] +
            8 * $inn[7] +
            9 * $inn[8] +
            10 * $inn[9];
            11 * $inn[10];
        $sum = $multiplication % 11;
        if ($sum == 10) {
            $sum =
                1 * $inn[10] +
                2 * $inn[11] +
                3 * $inn[1] +
                4 * $inn[2] +
                5 * $inn[3] +
                6 * $inn[4] +
                7 * $inn[5] +
                8 * $inn[6] +
                9 * $inn[7] +
                10 * $inn[8] +
                11 * $inn[9];
        }

        return $sum;
    }

//    private static function generateSum($inn): int
//    {
//        $multiplication =
//            7 * $inn[0] +
//            2 * $inn[1] +
//            4 * $inn[2] +
//            10 * $inn[3] +
//            3 * $inn[4] +
//            5 * $inn[5] +
//            9 * $inn[6] +
//            4 * $inn[7] +
//            6 * $inn[8] +
//            8 * $inn[9];
//        return $multiplication % 11 % 10;
//    }
}

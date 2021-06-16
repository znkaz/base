<?php

namespace ZnKaz\Base\Domain\Helpers;

use Exception;
use ZnCore\Base\Enums\Measure\TimeEnum;
use ZnKaz\Base\Domain\Entities\DateEntity;
use ZnKaz\Base\Domain\Entities\IinEntity;
use ZnKaz\Base\Domain\Enums\TypeEnum;

class IinDateHelper
{

    public static function parseDateIndividual($value): DateEntity
    {
        $smallYear = substr($value, 0, 2);
        $dateEntity = new DateEntity();
        
        $century = substr($value, 6, 1);
        $epoch = self::getEpoch($century);
        $dateEntity->setYear($epoch . $smallYear);
        $dateEntity->setMonth(substr($value, 2, 2));
        $dateEntity->setDay(substr($value, 4, 2));

        if (!self::validateDate($dateEntity)) {
            throw new Exception('Birthday not valid');
        }
        return $dateEntity;
    }

    public static function parseDateJuridical($value): DateEntity
    {
        $smallYear = substr($value, 0, 2);
        $dateEntity = new DateEntity();

        $currentSmallYear = intval(date('y'));
        if($currentSmallYear > intval($smallYear)) {
            $dateEntity->setYear('20' . $smallYear);
        } else {
            $dateEntity->setYear('19' . $smallYear);
        }
        $dateEntity->setMonth(substr($value, 2, 2));
        $dateEntity->setDay( '01');
        
        if (!self::validateDate($dateEntity)) {
            throw new Exception('Birthday not valid');
        }
        return $dateEntity;
    }
    
    public static function parseDate(IinEntity $iinEntity): DateEntity
    {
        $value = $iinEntity->getValue();

        $smallYear = substr($value, 0, 2);


        $dateEntity = new DateEntity();

        if($iinEntity->getType() == TypeEnum::INDIVIDUAL) {
            $century = substr($value, 6, 1);
            $epoch = self::getEpoch($century);
            //$dateEntity->setEpoch($epoch);
            $dateEntity->setYear($epoch . $smallYear);
            $dateEntity->setMonth(substr($value, 2, 2));
            $dateEntity->setDay(substr($value, 4, 2));
        } elseif($iinEntity->getType() == TypeEnum::JURIDICAL) {
            $currentSmallYear = intval(date('y'));
            if($currentSmallYear > intval($smallYear)) {
                $dateEntity->setYear('20' . $smallYear);
            } else {
                $dateEntity->setYear('19' . $smallYear);
            }
            $dateEntity->setMonth(substr($value, 2, 2));
            $dateEntity->setDay( '01');
        }

        if (!self::validateDate($dateEntity)) {
            throw new Exception('Birthday not valid');
        }
        return $dateEntity;
    }

    private static function validateDate(DateEntity $dateEntity): bool
    {
        return checkdate($dateEntity->getMonth(), $dateEntity->getDay(), $dateEntity->getYear());
    }

    private static function getEpoch(int $century): int
    {
        $residue = $century % 2;
        if ($residue == 0) {
            $century--;
        }
        $centuryDiv = floor($century / 2);
        return $centuryDiv + 18;
    }

    private static function dateStringToTimestamp(string $dateString): int
    {
        list($year, $month, $day) = explode('-', $dateString);
        return
            $year * TimeEnum::SECOND_PER_YEAR +
            $month * TimeEnum::SECOND_PER_MONTH +
            $day * TimeEnum::SECOND_PER_DAY;
    }

    private static function getNowDateAsString(): string
    {
        return date('Y-m-d', time());
    }

    private static function dateToString(DateEntity $dateEntity): string
    {
        return $dateEntity->getYear() . '-' . $dateEntity->getMonth() . '-' . $dateEntity->getDay();
    }
}

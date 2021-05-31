<?php

namespace ZnKaz\Base\Domain\Helpers;

use Exception;
use ZnCore\Base\Enums\Measure\TimeEnum;
use ZnKaz\Base\Domain\Entities\DateEntity;
use ZnKaz\Base\Domain\Entities\IinEntity;

class IinDateHelper
{

    public static function parseDate(IinEntity $iinEntity): DateEntity
    {
        $value = $iinEntity->getValue();
        $age = self::getAge($iinEntity->getCentury());
        $smallYear = substr($value, 0, 2);
        $dateEntity = new DateEntity();
        $dateEntity->setAge($age);
        $dateEntity->setYear($age . $smallYear);
        $dateEntity->setMonth(substr($value, 2, 2));
        $dateEntity->setDay(substr($value, 4, 2));
        if (!self::validateDate($dateEntity)) {
            throw new Exception('Birthday not valid');
        }
        //self::getOld($dateEntity);
        return $dateEntity;
    }

    private static function getOld(DateEntity $dateEntity): int
    {
        $birthDateString = self::dateToString($dateEntity);
        $nowDateString = self::getNowDateAsString();
        $diffSec = self::dateStringToTimestamp($nowDateString) - self::dateStringToTimestamp($birthDateString);
        $yearCount = floor($diffSec / TimeEnum::SECOND_PER_YEAR);
        if ($yearCount <= 0) {
            throw new Exception('');
        }
        return $yearCount;
    }

    private static function validateDate(DateEntity $dateEntity): bool
    {
        return checkdate($dateEntity->getMonth(), $dateEntity->getDay(), $dateEntity->getYear());
    }

    private static function getAge(int $century): int
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

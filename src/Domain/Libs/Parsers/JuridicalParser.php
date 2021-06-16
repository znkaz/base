<?php

namespace ZnKaz\Base\Domain\Libs\Parsers;

use ZnCore\Domain\Exceptions\UnprocessibleEntityException;
use ZnKaz\Base\Domain\Entities\BaseEntity;
use ZnKaz\Base\Domain\Entities\IinEntity;
use ZnKaz\Base\Domain\Entities\IndividualEntity;
use ZnKaz\Base\Domain\Entities\JuridicalEntity;
use ZnKaz\Base\Domain\Enums\SexEnum;
use ZnKaz\Base\Domain\Helpers\IinDateHelper;

class JuridicalParser implements ParserInterface
{

    private $dateParser;

    public function __construct()
    {
        $this->dateParser = new JuridicalDateParser();
    }

    public function parse(string $value): BaseEntity
    {
        $birthday = $this->dateParser->parse($value);
        
        $juridicalEntity = new JuridicalEntity();
        $juridicalEntity->setValue($value);
        $juridicalEntity->setType($value[4]);
        $juridicalEntity->setPart($value[5]);
        $juridicalEntity->setRegistrationDate($birthday);

        $juridicalEntity->setSerialNumber(substr($value, 6, 5));
        $juridicalEntity->setCheckSum(substr($value, 11, 1));
        return $juridicalEntity;
    }
}

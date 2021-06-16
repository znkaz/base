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
use ZnKaz\Base\Domain\Libs\Parsers\IndividualParser;
use ZnKaz\Base\Domain\Libs\Validator;

class IinParser
{

    public static function parse($value): IinEntity
    {
        $validator = new Validator();
        $validator->validate($value);
        
        $iinEntity = new IinEntity();
        $iinEntity->setValue($value);
        $iinEntity->setType(self::getType($value));
        $iinEntity->setCheckSum(substr($value, 11, 1));

        try {
            $birthday = IinDateHelper::parseDate($iinEntity);
        } catch (\Exception $e) {
            $unprocessibleEntityException = new UnprocessibleEntityException();
            $unprocessibleEntityException->add('birthday', $e->getMessage());
            throw $unprocessibleEntityException;
        }

        if ($iinEntity->getType() == TypeEnum::INDIVIDUAL) {
            $parser = new IndividualParser();
            $individualEntity = $parser->parse($value);
            $iinEntity->setIndividual($individualEntity);
            
            /*$individualEntity = new IndividualEntity();
            $individualEntity->setSex(self::getSex($iinEntity));
            $individualEntity->setCentury(substr($value, 6, 1));
            self::validateCentury($individualEntity->getCentury());
            $individualEntity->setBirthday($birthday);
            $iinEntity->setIndividual($individualEntity);*/

            $iinEntity->setSerialNumber(substr($value, 7, 4));
        } elseif ($iinEntity->getType() == TypeEnum::JURIDICAL) {
            $juridicalEntity = new JuridicalEntity();
            $juridicalEntity->setType($value[4]);
            $juridicalEntity->setPart($value[5]);
            $juridicalEntity->setRegistrationDate($birthday);
            $iinEntity->setJuridical($juridicalEntity);
            
            $iinEntity->setSerialNumber(substr($value, 6, 5));
        }

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

}

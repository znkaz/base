<?php

namespace ZnKaz\Base\Domain\Helpers;

use ZnKaz\Base\Domain\Entities\BaseEntity;
use ZnKaz\Base\Domain\Enums\TypeEnum;
use ZnKaz\Base\Domain\Exceptions\BadTypeException;
use ZnKaz\Base\Domain\Libs\Parsers\IndividualParser;
use ZnKaz\Base\Domain\Libs\Parsers\JuridicalParser;
use ZnKaz\Base\Domain\Libs\Parsers\ParserInterface;
use ZnKaz\Base\Domain\Libs\Validator;

class IinParser
{

    public static function parse($value): BaseEntity
    {
        $validator = new Validator();
        $validator->validate($value);
        $type = self::getType($value);
        $parser = self::getParserByType($type);
        return $parser->parse($value);
    }

    private static function getParserByType(string $type): ParserInterface
    {
        if ($type == TypeEnum::INDIVIDUAL) {
            return new IndividualParser();
        } elseif ($type == TypeEnum::JURIDICAL) {
            return new JuridicalParser();
        }
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
        throw new BadTypeException('Error type');
    }
}

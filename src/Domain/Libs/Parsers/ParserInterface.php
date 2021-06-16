<?php

namespace ZnKaz\Base\Domain\Libs\Parsers;

use ZnKaz\Base\Domain\Entities\BaseEntity;

interface ParserInterface
{

    public function parse(string $value): BaseEntity;
}

<?php

namespace ZnKaz\Base\Domain\Libs\Parsers;

use ZnKaz\Base\Domain\Entities\DateEntity;

interface DateParserInterface
{

    public function parse(string $value): DateEntity;
}

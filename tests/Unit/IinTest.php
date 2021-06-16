<?php

namespace ZnCore\Base\Tests\Unit;

use ZnKaz\Base\Domain\Helpers\IinParser;
use ZnTool\Test\Base\BaseTest;

final class IinTest extends BaseTest
{

    public function testIndividualSuccess()
    {
        IinParser::parse('770712345674');
        IinParser::parse('921104356789');
        
        $iinEntity = IinParser::parse('870620312341');
        $this->assertEquals('individual', $iinEntity->getType());
    }

    public function testJuridicalSuccess()
    {
        IinParser::parse('090440002978');
        IinParser::parse('000140001813');
        IinParser::parse('961240001036');
        IinParser::parse('961240001016');
        IinParser::parse('970240000890');

        $iinEntity = IinParser::parse('050340004626');
        $this->assertEquals('juridical', $iinEntity->getType());
    }
}

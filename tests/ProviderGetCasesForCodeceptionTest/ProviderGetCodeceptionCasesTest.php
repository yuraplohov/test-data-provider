<?php

namespace Yuraplohov\TestdataProvider\Test;

use PHPUnit\Framework\TestCase;
use Yuraplohov\TestDataProvider\Provider;

class ProviderGetCodeceptionCasesTest extends TestCase
{
    /** @test */
    public function it_gets_cases_for_codeception()
    {
        $sut = new Provider();

        $result = $sut->getCodeceptionCases([
            'case1',
            'case2',
        ]);

        $this->assertEquals([
            'case1' => [
                'php_file1' => [
                    'var1' => 'value1',
                    'var2' => 100,
                    'var3' => false,
                    'var4' => null,
                ],
                'php_file2' => [
                    'var5' => [
                        'var6' => 100,
                        'var7' => false,
                        'var8' => null,
                    ]
                ],
                'xml_file' => '<?xml version="1.0" encoding="UTF-8"?>
<message>
    <warning>
        Hello World 1
    </warning>
</message>',
                'json_file' => '{
  "name":"Юрий",
  "age":30,
  "car":null
}',
            ],
            'case2' => [
                'php_file1' => [
                    'var1' => 'value2',
                    'var2' => 200,
                    'var3' => true,
                    'var4' => null,
                ],
                'php_file2' => [
                    'var5' => [
                        'var6' => 200,
                        'var7' => true,
                        'var8' => null,
                    ]
                ],
                'xml_file' => '<?xml version="1.0" encoding="UTF-8"?>
<message>
    <warning>
        Hello World 2
    </warning>
</message>',
                'json_file' => '{
  "name":"Александр",
  "age":27,
  "car":null
}',
            ],
        ], $result);
    }
}

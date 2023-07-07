<?php

namespace Yuraplohov\TestdataProvider\Test;

use PHPUnit\Framework\TestCase;
use Yuraplohov\TestDataProvider\Provider;

class ProviderGetTest extends TestCase
{
    /** @test */
    public function it_gets_array()
    {
        $sut = new Provider();

        $result = $sut->get('file');

        $this->assertEquals([
            'var1' => 'value1',
            'var2' => 100,
            'var3' => false,
            'var4' => null,
            'var5' => [
                'var6' => 100,
                'var7' => false,
                'var8' => null,
            ]
        ], $result);
    }

    /** @test */
    public function it_gets_element_of_first_array_level()
    {
        $sut = new Provider();

        $result = $sut->get('file.var5');

        $this->assertEquals([
            'var6' => 100,
            'var7' => false,
            'var8' => null,
        ], $result);
    }

    /** @test */
    public function it_gets_element_of_second_array_level()
    {
        $sut = new Provider();

        $result = $sut->get('file.var5.var6');

        $this->assertEquals(100, $result);
    }

    /** @test */
    public function it_gets_dir_of_first_level()
    {
        $sut = new Provider();

        $result = $sut->get('dir');

        $this->assertEquals([
            'file1' => [
                'var1' => 'value1',
                'var2' => 100,
                'var3' => false,
                'var4' => null,
            ],
            'file2' => [
                'var1' => 'value1',
                'var2' => [
                    'var3' => false,
                    'var4' => null,
                ]
            ],
        ], $result);
    }

    /** @test */
    public function it_gets_dir_of_second_level()
    {
        $sut = new Provider();

        $result = $sut->get('dir1/dir');

        $this->assertEquals([
            'file1' => [
                'var1' => 'value1',
                'var2' => 100,
                'var3' => false,
                'var4' => null,
            ],
            'file2' => [
                'var1' => 'value1',
                'var2' => [
                    'var3' => false,
                    'var4' => null,
                ]
            ],
        ], $result);
    }

    /** @test */
    public function it_gets_xml_file()
    {
        $sut = new Provider();

        $result = $sut->get('xml_file');

        $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?>
<message>
    <warning>
        Hello World
    </warning>
</message>', $result);
    }

    /** @test */
    public function it_gets_json_file()
    {
        $sut = new Provider();

        $result = $sut->get('json_file');

        $this->assertEquals('{
  "name":"Юрий",
  "age":30,
  "car":null
}', $result);
    }

    /** @test */
    public function it_gets_txt_file()
    {
        $sut = new Provider();

        $result = $sut->get('txt_file');

        $this->assertEquals('Hello World
Hello World
Hello World', $result);
    }

    /** @test */
    public function it_throws_exception_about_not_existing_file()
    {
        $this->expectException(\InvalidArgumentException::class);

        $sut = new Provider();

        $result = $sut->get('some_file');
    }

    /** @test */
    public function it_gets_data_with_base_path()
    {
        $sut = new Provider();

        $result = $sut->basePath(__DIR__ . DIRECTORY_SEPARATOR . 'files/')->get('file1');

        $this->assertEquals([
            'var1' => 'value1',
        ], $result);
    }
}

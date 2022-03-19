## yuraplohov/test-data-provider

The package provides data for autotests from php-files with returned arrays, and from any text files (json, xml, txt...).
The package works with PHPUnit, Codeception and Pest frameworks.

## Installation

``` bash
composer require yuraplohov/test-data-provider
```

## Usage

The package contains one class - **Yuraplohov\TestDataProvider\Provider**. This class has three public methods:

1. **getPHPUnitCases(array $caseDirs): array** - retrieves an array of test cases in PHPUnit framework format.
2. **getCodeceptionCases(array $caseDirs): array** - retrieves an array of test cases in Codeception framework format.
3. **get(string $path): mixed** - retrieves data from a directory, file, or array element in a php-file.

Php files with data for all these methods should be like this:

``` php
<?php

return [
    // some large array
];
```

Also, all three methods can work with any text files (json, xml, txt...).
The content of such files is returned as a string without any transformations.

The 'tests' directory structure for **getPHPUnitCases()** and **getCodeceptionCases()** methods could be:

* tests
  * Example_Test
    * ExampleTets.php
    * data
      * case_1
        * input.json
        * expected.php
      * case_2
        * input.json                                               
        * expected.php

The **'data'** directory must be at the same level as your test class. Directories with cases are placed in the **'data'** directory. 
You can name case directories whatever you want, but the **'data'** directory must always be named like this.

### Provider::getPHPUnitCases(array $caseDirs): array

``` php
/**
 * @test
 * @dataProvider someProvider
 */
public function it_gets_some_result(array $case)
{
    $sut = new SomeClass;

    $this->assertEquals($case['expected'], $sut->someMethod($case['input']));
}

/**
 * @return array
 */
public function someProvider(): array
{
    return (new Provider)->getPHPUnitCases([
        'case_1',
        'case_2',
    ]);
}
```

### Provider::getCodeceptionCases(array $caseDirs): array

``` php
/**
 * @param UnitTester $I
 * @param \Codeception\Example $example
 * @dataProvider someProvider
 */
public function it_gets_some_result(UnitTester $I, \Codeception\Example $example)
{
    $sut = new SomeClass;

    $I->assertEquals($example['expected'], $sut->someMethod($example['input']));
}

/**
 * @return array
 */
protected function someProvider()
{
    return (new Provider)->getCodeceptionCases([
        'case_1',
        'case_2',
    ]);
}
```

### Provider::get(string $path): mixed

This method can be used with any framework and with any structure of the 'data' directory.

Tests directory structure example:

* tests
  * Example_Test
    * ExampleTets.php
    * data
      * service
        * input.json
        * settings.php
      * expected.php

Examples of method calling for this structure:

``` php
(new Provider)->get('service'); // retrieves array with all content of the 'data/service' directory.

(new Provider)->get('service/input'); // retrieves content of the file 'data/service/input.json'.

(new Provider)->get('expected'); // retrieves array from the 'data/expected.php' file.

(new Provider)->get('service/settings.foo.bar'); // retrieves value of the 'bar' array element.

// foo.bar - array elements hierarchy in the 'settings.php' file 
```

Usage in PHPUnit:

``` php
/** @test */
public function it_gets_some_result()
{
    $dp = new Provider;

    $sut = new SomeClass($dp->get('service/settings'));

    $this->assertEquals($dp->get('expected'), $sut->someMethod($dp->get('service/input')));
}
```

## Testing

``` bash
./vendor/bin/phpunit
```

## License

The MIT License (MIT).
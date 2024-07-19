<?php

use PHPUnit\Framework\TestCase;

class ilAssLacOperationManufacturerTest extends TestCase
{
    public const ALL_IN_ONE_PATTERN = '/&|\||(?<!<|>)=|<(?!=|>)|>(?!=)|<=|>=|<>/';

    public function test_singleton()
    {
        $instance1 = ilAssLacOperationManufacturer::_getInstance();
        $instance2 = ilAssLacOperationManufacturer::_getInstance();

        $this->assertSame($instance1, $instance2);
        $this->assertEquals(self::ALL_IN_ONE_PATTERN, $instance1->getPattern());
        $this->assertInstanceOf(ilAssLacManufacturerInterface::class, $instance1);
        $this->assertInstanceOf(ilAssLacAbstractManufacturer::class, $instance1);
    }

    /**
     * @dataProvider provideManufactureData
     */
    public function test_manufacture(string $input, string $expectedExpressionType): void
    {
        $factory = ilAssLacOperationManufacturer::_getInstance();
        $expression = $factory->manufacture($input);

        $this->assertInstanceOf($expectedExpressionType, $expression);
    }

    public function test_manufacture_invalid_expression(): void
    {
        $this->expectException(ilAssLacUnsupportedOperation::class);

        $factory = ilAssLacOperationManufacturer::_getInstance();
        $factory->manufacture('invalid');
    }

    /**
     * @dataProvider provideMatchData
     */
    public function test_match(string $input, int $expectedMatchesCount): void
    {
        $instance = ilAssLacOperationManufacturer::_getInstance();
        $this->assertCount($expectedMatchesCount, $instance->match($input));
    }

    public function test_match_invalid_input(): void
    {
        $this->expectException(ilAssLacUnableToParseCondition::class);

        $instance = ilAssLacOperationManufacturer::_getInstance();
        $instance->match('invalid');
    }

    public static function provideManufactureData(): array
    {
        return [
            ['&', ilAssLacAndOperation::class],
            ['|', ilAssLacOrOperation::class],
            ['=', ilAssLacEqualsOperation::class],
            ['<', ilAssLacLesserOperation::class],
            ['>', ilAssLacGreaterOperation::class],
            ['<=', ilAssLacLesserOrEqualsOperation::class],
            ['>=', ilAssLacGreaterOrEqualsOperation::class],
            ['<>', ilAssLacNotEqualsOperation::class],
        ];
    }

    public static function provideMatchData(): array
    {
        return [
            ['R[2] & Q4 = +3+', 2],
            ['R = %89.5%', 1],
        ];
    }
}

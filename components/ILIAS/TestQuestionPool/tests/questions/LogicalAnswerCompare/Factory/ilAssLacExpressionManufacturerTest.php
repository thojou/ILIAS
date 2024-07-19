<?php

use PHPUnit\Framework\TestCase;

class ilAssLacExpressionManufacturerTest extends TestCase
{
    public const ALL_IN_ONE_PATTERN = '/%[0-9\.]+%|#-?[0-9\.]+#|\+[0-9]+\+|(Q\d+)(?=\=|<|>|\s|$)|(R)(?=\=|<|>|\s|$)|Q[0-9]+\[[0-9]+\]|R\[[0-9]+\]|~.*?~|;[0-9]+:[0-9]+;|\$[0-9]+(?:,[0-9]+)*\$|\*[0-9]+(?:,[0-9]+)*\*|(\?)/';

    public function test_singleton()
    {
        $instance1 = ilAssLacExpressionManufacturer::_getInstance();
        $instance2 = ilAssLacExpressionManufacturer::_getInstance();

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
        $factory = ilAssLacExpressionManufacturer::_getInstance();
        $expression = $factory->manufacture($input);

        $this->assertInstanceOf($expectedExpressionType, $expression);
    }

    public function test_manufacture_invalid_expression(): void
    {
        $this->expectException(ilAssLacUnsupportedExpression::class);

        $factory = ilAssLacExpressionManufacturer::_getInstance();
        $factory->manufacture('invalid');
    }

    /**
     * @dataProvider provideMatchData
     */
    public function test_match(string $input, int $expectedMatchesCount): void
    {
        $instance = ilAssLacExpressionManufacturer::_getInstance();
        $this->assertCount($expectedMatchesCount, $instance->match($input));
    }

    public function test_match_invalid_input(): void
    {
        $this->expectException(ilAssLacUnableToParseCondition::class);

        $instance = ilAssLacExpressionManufacturer::_getInstance();
        $instance->match('invalid');
    }

    public static function provideManufactureData(): array
    {
        return [
            ['Q1[5]', ilAssLacResultOfAnswerOfQuestionExpression::class],
            ['R[2]', ilAssLacResultOfAnswerOfCurrentQuestionExpression::class],
            ['Q4', ilAssLacAnswerOfQuestionExpression::class],
            ['R', ilAssLacAnswerOfCurrentQuestionExpression::class],
            ['%89.5%', ilAssLacPercentageResultExpression::class],
            ['+3+', ilAssLacNumberOfResultExpression::class],
            ['#42#', ilAssLacNumericResultExpression::class],
            ['~Hello World~', ilAssLacStringResultExpression::class],
            ['$2,3,5,4,1$', ilAssLacOrderingResultExpression::class],
            ['*1,2,3,4,5*', ilAssLacExclusiveResultExpression::class],
            [';2:3;', ilAssLacMatchingResultExpression::class],
            ['?', ilAssLacEmptyAnswerExpression::class],
        ];
    }

    public static function provideMatchData(): array
    {
        return [
            ['Q1[5]', 1],
            ['R[2] & Q4 = +3+', 3],
            ['R = %89.5%', 2],
        ];
    }
}

<?php

use PHPUnit\Framework\TestCase;

abstract class ilAssLacExpressionTestCase extends TestCase
{
    protected ilAssLacAbstractExpression $expression;

    protected function setUp(): void
    {
        $this->expression = $this->createExpression();
    }

    public function test_instance(): void
    {
        $this->assertInstanceOf(ilAssLacAbstractExpression::class, $this->expression);
        $this->assertInstanceOf(ilAssLacCompositeInterface::class, $this->expression);
        $this->assertInstanceOf(ilAssLacAbstractComposite::class, $this->expression);

        $this->assertEquals($this->getExpectedIdentifier(), $this->getExpressionClass()::$identifier);
        $this->assertEquals($this->getExpectedStaticPattern(), $this->getExpressionClass()::$pattern);
    }

    /**
     * @dataProvider provideParseValueData
     */
    public function test_parseValue(string $input_value, string $expected_value, string $expected_description): void
    {
        $this->expression->parseValue($input_value);

        $this->assertEquals($expected_value, $this->expression->getValue());
        $this->assertEquals($expected_description, $this->expression->getDescription());
    }


    protected function createExpression(): ilAssLacAbstractExpression
    {
        $expression_class = $this->getExpressionClass();
        return new $expression_class();
    }


    abstract public static function provideParseValueData(): array;
    abstract protected function getExpressionClass(): string;
    abstract protected function getExpectedStaticPattern(): string;
    abstract protected function getExpectedIdentifier(): string;
}

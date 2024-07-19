<?php

use PHPUnit\Framework\TestCase;

abstract class ilAssLacExpressionTestCase extends TestCase
{
    protected ilAssLacAbstractExpression $expression;

    protected function setUp(): void
    {
        $expression_class = $this->getExpressionClass();
        $this->expression = new $expression_class();
        $this->expression->parseValue($this->getInputValueFixture());
    }

    public function test_instance(): void
    {

        $this->assertInstanceOf(ilAssLacAbstractExpression::class, $this->expression);
        $this->assertInstanceOf(ilAssLacCompositeInterface::class, $this->expression);
        $this->assertInstanceOf(ilAssLacAbstractComposite::class, $this->expression);

        $this->assertEquals($this->getExpectedValue(), $this->expression->getValue());
        $this->assertEquals($this->getExpectedDescription(), $this->expression->getDescription());

        $this->assertEquals($this->getExpectedIdentifier(), $this->getExpressionClass()::$identifier);
        $this->assertEquals($this->getExpectedStaticPattern(), $this->getExpressionClass()::$pattern);

    }

    abstract protected function getExpressionClass(): string;
    abstract protected function getExpectedValue(): string;
    abstract protected function getExpectedDescription(): string;
    abstract protected function getExpectedStaticPattern(): string;
    abstract protected function getExpectedIdentifier(): string;
    abstract protected function getInputValueFixture(): string;
}

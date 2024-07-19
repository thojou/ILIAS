<?php

class ilAssLacStringResultExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacStringResultExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return '~Hello World~';
    }

    protected function getExpectedDescription(): string
    {
        return "Hello World beantwortet "; // TODO: Implement getExpectedDescription() method.
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/~.*?~/';
    }

    protected function getExpectedIdentifier(): string
    {
        return '~TEXT~';
    }

    protected function getInputValueFixture(): string
    {
        return '~Hello World~';
    }

}

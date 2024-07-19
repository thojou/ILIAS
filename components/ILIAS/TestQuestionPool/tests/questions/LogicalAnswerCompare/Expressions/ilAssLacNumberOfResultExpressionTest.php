<?php

class ilAssLacNumberOfResultExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacNumberOfResultExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return "+42+";
    }

    protected function getExpectedDescription(): string
    {
        return "Anwort 42 beantwortet "; // TODO: Implement getExpectedDescription() method.
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/\\+[0-9]+\\+/';
    }

    protected function getExpectedIdentifier(): string
    {
        return "+n+";
    }

    protected function getInputValueFixture(): string
    {
        return "+42+";
    }
}

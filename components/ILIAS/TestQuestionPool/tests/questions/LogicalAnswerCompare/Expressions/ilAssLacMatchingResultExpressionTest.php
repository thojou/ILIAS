<?php

class ilAssLacMatchingResultExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacMatchingResultExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return ";2:3;";
    }

    protected function getExpectedDescription(): string
    {
        return "0 beantwortet "; // TODO: Implement getExpectedDescription() method.
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/;[0-9]+:[0-9]+;/';
    }

    protected function getExpectedIdentifier(): string
    {
        return ";n:m;";
    }

    protected function getInputValueFixture(): string
    {
        return ";2:3;";
    }
}

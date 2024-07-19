<?php

class ilAssLacEmptyAnswerExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacEmptyAnswerExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return "?";
    }

    protected function getExpectedDescription(): string
    {
        return " nicht beantwortet";
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/(\?)/';
    }

    protected function getExpectedIdentifier(): string
    {
        return "?";
    }

    protected function getInputValueFixture(): string
    {
        return "?";
    }
}

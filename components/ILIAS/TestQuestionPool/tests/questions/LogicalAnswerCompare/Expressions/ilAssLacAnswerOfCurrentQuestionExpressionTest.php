<?php

use PHPUnit\Framework\TestCase;

class ilAssLacAnswerOfCurrentQuestionExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacQuestionExpressionInterface::class, $this->expression);
        $this->assertNull($this->expression->getQuestionIndex());
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacAnswerOfCurrentQuestionExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return "R";
    }

    protected function getExpectedDescription(): string
    {
        return "Aktuelle Frage";
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/(R)(?=\=|<|>|\s|$)/';
    }

    protected function getExpectedIdentifier(): string
    {
        return "R";
    }

    protected function getInputValueFixture(): string
    {
        return "R";
    }
}

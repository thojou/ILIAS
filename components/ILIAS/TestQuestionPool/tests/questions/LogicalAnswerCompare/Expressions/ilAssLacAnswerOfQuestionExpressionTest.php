<?php

class ilAssLacAnswerOfQuestionExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacQuestionExpressionInterface::class, $this->expression);
        $this->assertEquals(5, $this->expression->getQuestionIndex());
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacAnswerOfQuestionExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return "Q5";
    }

    protected function getExpectedDescription(): string
    {
        return "Frage 5 ";
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/(Q\d+)(?=\=|<|>|\s|$)/';
    }

    protected function getExpectedIdentifier(): string
    {
        return "Qn";
    }

    protected function getInputValueFixture(): string
    {
        return "Q5";
    }
}

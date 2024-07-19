<?php

namespace questions\LogicalAnswerCompare\Expressions;

use ilAssLacExpressionTestCase;
use ilAssLacNumericResultExpression;
use ilAssLacOrderingResultExpression;
use ilAssLacPercentageResultExpression;
use ilAssLacQuestionExpressionInterface;
use ilAssLacResultOfAnswerOfCurrentQuestionExpression;
use ilAssLacResultOfAnswerOfQuestionExpression;
use ilAssLacSolutionExpressionInterface;

class ilAssLacResultOfAnswerOfQuestionExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacQuestionExpressionInterface::class, $this->expression);
        $this->assertEquals(1, $this->expression->getQuestionIndex());
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacResultOfAnswerOfQuestionExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return 'Q1[3]';
    }

    protected function getExpectedDescription(): string
    {
        return "Frage 1 mit Anwort 3 beantwortet "; // TODO: Implement getExpectedDescription() method.
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/Q[0-9]+\\[[0-9]+\\]/';
    }

    protected function getExpectedIdentifier(): string
    {
        return 'Qn[m]';
    }

    protected function getInputValueFixture(): string
    {
        return 'Q1[3]';
    }
}

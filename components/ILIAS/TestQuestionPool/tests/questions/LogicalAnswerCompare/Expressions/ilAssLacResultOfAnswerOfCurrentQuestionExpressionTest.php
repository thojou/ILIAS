<?php

namespace questions\LogicalAnswerCompare\Expressions;

use ilAssLacExpressionTestCase;
use ilAssLacNumericResultExpression;
use ilAssLacOrderingResultExpression;
use ilAssLacPercentageResultExpression;
use ilAssLacQuestionExpressionInterface;
use ilAssLacResultOfAnswerOfCurrentQuestionExpression;
use ilAssLacSolutionExpressionInterface;

class ilAssLacResultOfAnswerOfCurrentQuestionExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacQuestionExpressionInterface::class, $this->expression);
        $this->assertNull($this->expression->getQuestionIndex());
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacResultOfAnswerOfCurrentQuestionExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return 'R[3]';
    }

    protected function getExpectedDescription(): string
    {
        return "Aktuelle Frage mit Anwort 3 beantwortet "; // TODO: Implement getExpectedDescription() method.
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/R\\[[0-9]+\\]/';
    }

    protected function getExpectedIdentifier(): string
    {
        return 'R[m]';
    }

    protected function getInputValueFixture(): string
    {
        return 'R[3]';
    }
}

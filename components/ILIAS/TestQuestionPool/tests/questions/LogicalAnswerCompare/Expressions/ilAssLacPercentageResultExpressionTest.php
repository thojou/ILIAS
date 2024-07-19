<?php

namespace questions\LogicalAnswerCompare\Expressions;

use ilAssLacExpressionTestCase;
use ilAssLacNumericResultExpression;
use ilAssLacOrderingResultExpression;
use ilAssLacPercentageResultExpression;
use ilAssLacSolutionExpressionInterface;

class ilAssLacPercentageResultExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacPercentageResultExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return '%75.3%';
    }

    protected function getExpectedDescription(): string
    {
        return "75.3% beantwortet "; // TODO: Implement getExpectedDescription() method.
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/%[0-9\.]+%/';
    }

    protected function getExpectedIdentifier(): string
    {
        return '%n%';
    }

    protected function getInputValueFixture(): string
    {
        return '%75.3%';
    }
}

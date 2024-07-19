<?php

namespace questions\LogicalAnswerCompare\Expressions;

use ilAssLacExpressionTestCase;
use ilAssLacNumericResultExpression;
use ilAssLacOrderingResultExpression;
use ilAssLacSolutionExpressionInterface;

class ilAssLacOrderingResultExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacOrderingResultExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return '$3,2,4,1,5$';
    }

    protected function getExpectedDescription(): string
    {
        return "3,2,4,1,5 beantwortet "; // TODO: Implement getExpectedDescription() method.
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/\$[0-9]+(?:,[0-9]+)*\$/';
    }

    protected function getExpectedIdentifier(): string
    {
        return '$n,m,o,p$';
    }

    protected function getInputValueFixture(): string
    {
        return '$3,2,4,1,5$';
    }

}

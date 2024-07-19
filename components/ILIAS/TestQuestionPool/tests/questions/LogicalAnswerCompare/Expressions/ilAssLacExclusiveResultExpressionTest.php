<?php

namespace questions\LogicalAnswerCompare\Expressions;

use ilAssLacEmptyAnswerExpression;
use ilAssLacExclusiveResultExpression;
use ilAssLacExpressionTestCase;
use ilAssLacSolutionExpressionInterface;

class ilAssLacExclusiveResultExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacExclusiveResultExpression::class;
    }

    protected function getExpectedValue(): string
    {
        return "*1,4,7*";
    }

    protected function getExpectedDescription(): string
    {
        return "1,4,7 beantwortet ";
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/\*[0-9]+(?:,[0-9]+)*\*/';
    }

    protected function getExpectedIdentifier(): string
    {
        return "*n,m,o,p*";
    }

    protected function getInputValueFixture(): string
    {
        return "*1,4,7*";
    }
}

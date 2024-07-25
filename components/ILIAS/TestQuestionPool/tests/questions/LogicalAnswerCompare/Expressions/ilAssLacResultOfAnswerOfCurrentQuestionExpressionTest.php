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

    protected function getExpectedStaticPattern(): string
    {
        return '/R\\[[0-9]+\\]/';
    }

    protected function getExpectedIdentifier(): string
    {
        return 'R[m]';
    }

    public static function provideParseValueData(): array
    {
        return [
            ['R[3]', 'R[3]', 'Aktuelle Frage mit Anwort 3 beantwortet '],
            ['R[1]', 'R[1]', 'Aktuelle Frage mit Anwort 1 beantwortet ']
        ];
    }
}

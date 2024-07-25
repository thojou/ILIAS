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
    }

    /**
     * @dataProvider provideParseValueData
     */
    public function test_parseValue(string $input_value, string $expected_value, string $expected_description): void
    {
        parent::test_parseValue($input_value, $expected_value, $expected_description);

        $this->assertIsInt($this->expression->getQuestionIndex());
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacResultOfAnswerOfQuestionExpression::class;
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/Q[0-9]+\\[[0-9]+\\]/';
    }

    protected function getExpectedIdentifier(): string
    {
        return 'Qn[m]';
    }

    public static function provideParseValueData(): array
    {
        return [
            ['Q1[3]', 'Q1[3]', 'Frage 1 mit Anwort 3 beantwortet '],
            ['Q2[1]', 'Q2[1]', 'Frage 2 mit Anwort 1 beantwortet ']
        ];
    }
}

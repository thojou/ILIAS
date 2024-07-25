<?php

namespace questions\LogicalAnswerCompare\Expressions;

use ilAssLacAbstractExpression;
use ilAssLacExpressionTestCase;
use ilAssLacNumericResultExpression;
use ilAssLacOrderingResultExpression;
use ilAssLacPercentageResultExpression;
use ilAssLacSolutionExpressionInterface;
use ilAssLacSolutionExpressionTestCaseTrait;
use ilUserQuestionResult;
use PHPUnit\Framework\MockObject\MockObject;

class ilAssLacPercentageResultExpressionTest extends ilAssLacExpressionTestCase
{
    use ilAssLacSolutionExpressionTestCaseTrait {
        createQuestionResultMock as createQuestionResultMockTrait;
    }

    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacPercentageResultExpression::class;
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/%[0-9\.]+%/';
    }

    protected function getExpectedIdentifier(): string
    {
        return '%n%';
    }

    protected function createExpression(): ilAssLacAbstractExpression
    {
        $expression = parent::createExpression();
        $expression->parseValue('%75%');

        return $expression;
    }

    protected function createQuestionResultMock(
        mixed $solution,
        bool $has_solution = false
    ): ilUserQuestionResult|MockObject {
        $question_result_mock = $this->createQuestionResultMockTrait($solution, $has_solution);

        $question_result_mock
            ->expects($this->any())
            ->method('getReachedPercentage')
            ->willReturn($solution[0]);

        return $question_result_mock;
    }

    public static function provideParseValueData(): array
    {
        return [
            ['%75.3%', '%75.3%', '75.3% beantwortet '],
            ['%1%', '%1%', '1% beantwortet ']
        ];
    }

    public static function provideCheckResultData(): array
    {
        return [
            [
                "comparator" => "=",
                "expected" => true,
                'index' => null,
                "has_solution" => false,
                'solution' => [75]
            ],
            [
                "comparator" => "<>",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => [80]
            ],
            [
                "comparator" => "<",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => [60]
            ],
            [
                "comparator" => "<=",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => [75]
            ],
            [
                "comparator" => ">=",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => [75]
            ],
            [
                "comparator" => ">",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => [90]
            ],
            [
                "comparator" => "==",
                "expected" => false,
                'index' => null,
                'has_solution' => true,
                'solution' => [75]
            ],
        ];
    }
}

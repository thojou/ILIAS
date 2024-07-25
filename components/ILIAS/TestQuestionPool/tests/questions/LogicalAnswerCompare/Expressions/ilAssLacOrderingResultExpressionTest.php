<?php

namespace questions\LogicalAnswerCompare\Expressions;

use ilAssLacAbstractExpression;
use ilAssLacExpressionTestCase;
use ilAssLacNumericResultExpression;
use ilAssLacOrderingResultExpression;
use ilAssLacSolutionExpressionInterface;
use ilAssLacSolutionExpressionTestCaseTrait;

class ilAssLacOrderingResultExpressionTest extends ilAssLacExpressionTestCase
{
    use ilAssLacSolutionExpressionTestCaseTrait;

    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacOrderingResultExpression::class;
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/\$[0-9]+(?:,[0-9]+)*\$/';
    }

    protected function getExpectedIdentifier(): string
    {
        return '$n,m,o,p$';
    }

    protected function createExpression(): ilAssLacAbstractExpression
    {
        $expression = parent::createExpression();
        $expression->parseValue('$3,2,4,1,5$');

        return $expression;
    }

    public static function provideParseValueData(): array
    {
        return [
            ['$3,2,4,1,5$', '$3,2,4,1,5$', '3,2,4,1,5 beantwortet '],
            ['$1,3,2$', '$1,3,2$', '1,3,2 beantwortet ']
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
                'solution' => ['3', '2', '4', '1', '5']
            ],
            [
                "comparator" => "<>",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ['1', '3', '2']
            ],
            [
                "comparator" => "==",
                "expected" => false,
                'index' => null,
                'has_solution' => true,
                'solution' => ['3', '2', '4', '1', '5']
            ],
        ];
    }
}

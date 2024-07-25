<?php

namespace questions\LogicalAnswerCompare\Expressions;

use ilAssLacEmptyAnswerExpression;
use ilAssLacExclusiveResultExpression;
use ilAssLacExpressionTestCase;
use ilAssLacSolutionExpressionInterface;
use ilAssLacSolutionExpressionTestCaseTrait;

class ilAssLacExclusiveResultExpressionTest extends ilAssLacExpressionTestCase
{
    use ilAssLacSolutionExpressionTestCaseTrait;

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

    protected function createExpression(): \ilAssLacAbstractExpression
    {
        $expression = parent::createExpression();
        $expression->parseValue('*1,3,7,8*');

        return $expression;
    }

    public static function provideParseValueData(): array
    {
        return [
            ["*1,4,7*", "*1,4,7*", '1,4,7 beantwortet '],
            ["*4,1,5,3,2*", "*4,1,5,3,2*", '4,1,5,3,2 beantwortet ']
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
                'solution' => ["1","3","7","8"]
            ],
            [
                "comparator" => "<>",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ["1","3","8","9"]
            ],
            [
                "comparator" => "==",
                "expected" => false,
                'index' => null,
                'has_solution' => true,
                'solution' => ["1","3","7","8"]
            ],
        ];
    }
}

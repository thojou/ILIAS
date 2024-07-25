<?php

class ilAssLacNumberOfResultExpressionTest extends ilAssLacExpressionTestCase
{
    use ilAssLacSolutionExpressionTestCaseTrait;

    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacNumberOfResultExpression::class;
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/\\+[0-9]+\\+/';
    }

    protected function getExpectedIdentifier(): string
    {
        return "+n+";
    }

    protected function createExpression(): ilAssLacAbstractExpression
    {
        $expression = parent::createExpression();
        $expression->parseValue('+3+');

        return $expression;
    }

    public static function provideParseValueData(): array
    {
        return [
            ['+42+', '+42+', 'Anwort 42 beantwortet '],
            ['+1+', '+1+', 'Anwort 1 beantwortet ']
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
                'solution' => ["key" => "3"]
            ],
            [
                "comparator" => "<>",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ["key" => "5"]
            ],
            [
                "comparator" => "=",
                "expected" => true,
                'index' => 1,
                "has_solution" => false,
                'solution' => ["value" => "3"]
            ],
            [
                "comparator" => "<>",
                "expected" => true,
                'index' => 1,
                'has_solution' => true,
                'solution' => ['value' => '5']
            ],
            [
                "comparator" => "==",
                "expected" => false,
                'index' => null,
                'has_solution' => true,
                'solution' => ["key" => "3"]
            ],
        ];
    }
}

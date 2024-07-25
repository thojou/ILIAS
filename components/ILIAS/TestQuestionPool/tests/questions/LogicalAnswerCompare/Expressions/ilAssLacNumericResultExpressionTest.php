<?php

class ilAssLacNumericResultExpressionTest extends ilAssLacExpressionTestCase
{
    use ilAssLacSolutionExpressionTestCaseTrait;

    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacNumericResultExpression::class;
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/#-?[0-9\.]+#/';
    }

    protected function getExpectedIdentifier(): string
    {
        return "#n#";
    }

    public static function provideParseValueData(): array
    {
        return [
            ['#42#', '#42#', '42 beantwortet '],
            ['#1#', '#1#', '1 beantwortet ']
        ];
    }

    protected function createExpression(): ilAssLacAbstractExpression
    {
        $expression = parent::createExpression();
        $expression->parseValue('#3#');

        return $expression;
    }

    public static function provideCheckResultData(): array
    {
        return [
            [
                "comparator" => "=",
                "expected" => true,
                'index' => null,
                "has_solution" => false,
                'solution' => ["3"]
            ],
            [
                "comparator" => "<>",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ["5"]
            ],
            [
                "comparator" => "<",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ["2"]
            ],
            [
                "comparator" => "<=",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ["3"]
            ],
            [
                "comparator" => ">=",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ["3"]
            ],
            [
                "comparator" => ">",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ["6"]
            ],
            [
                "comparator" => "=",
                "expected" => true,
                'index' => 1,
                "has_solution" => false,
                'solution' => ['value' => "3"]
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
                'solution' => ["3"]
            ],
        ];
    }
}

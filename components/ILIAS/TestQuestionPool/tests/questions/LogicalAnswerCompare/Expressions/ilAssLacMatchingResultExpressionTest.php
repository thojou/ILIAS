<?php

class ilAssLacMatchingResultExpressionTest extends ilAssLacExpressionTestCase
{
    use ilAssLacSolutionExpressionTestCaseTrait;

    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacMatchingResultExpression::class;
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/;[0-9]+:[0-9]+;/';
    }

    protected function getExpectedIdentifier(): string
    {
        return ";n:m;";
    }

    protected function createExpression(): ilAssLacAbstractExpression
    {
        $expression = parent::createExpression();
        $expression->parseValue(';2:3;');

        return $expression;
    }

    public static function provideParseValueData(): array
    {
        // @todo: fix description
        return [
            [';2:3;', ';2:3;', '0 beantwortet '],
            [';3:4;', ';3:4;', '0 beantwortet '],
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
                'solution' => ["key" => "2", "value" => "3"]
            ],
            [
                "comparator" => "<>",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ["key" => "3", "value" => "2"]
            ],
            [
                "comparator" => "==",
                "expected" => false,
                'index' => null,
                'has_solution' => true,
                'solution' => ["key" => "2", "value" => "3"]
            ],
        ];
    }
}

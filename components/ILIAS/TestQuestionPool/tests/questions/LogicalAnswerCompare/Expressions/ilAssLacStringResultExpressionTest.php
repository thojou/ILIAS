<?php

class ilAssLacStringResultExpressionTest extends ilAssLacExpressionTestCase
{
    use ilAssLacSolutionExpressionTestCaseTrait;

    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacStringResultExpression::class;
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/~.*?~/';
    }

    protected function getExpectedIdentifier(): string
    {
        return '~TEXT~';
    }

    protected function createExpression(): ilAssLacAbstractExpression
    {
        $expression = parent::createExpression();
        $expression->parseValue('~Hello World~');

        return $expression;
    }

    public static function provideParseValueData(): array
    {
        return [
            ['~Hello World~', '~Hello World~', 'Hello World beantwortet '],
            ['~Hello~', '~Hello~', 'Hello beantwortet ']
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
                'solution' => ["value" => "Hello World"]
            ],
            [
                "comparator" => "<>",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ["value" => "Hello Universe"]
            ],
            [
                "comparator" => "=",
                "expected" => true,
                'index' => 1,
                'has_solution' => true,
                'solution' => ["value" => "Hello World"]
            ],
            [
                "comparator" => "==",
                "expected" => false,
                'index' => null,
                'has_solution' => true,
                'solution' => ["value" => "Hello World"]
            ],
        ];
    }
}

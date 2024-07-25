<?php

class ilAssLacEmptyAnswerExpressionTest extends ilAssLacExpressionTestCase
{
    use ilAssLacSolutionExpressionTestCaseTrait;

    public function test_QuestionSolutionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacSolutionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacEmptyAnswerExpression::class;
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/(\?)/';
    }

    protected function getExpectedIdentifier(): string
    {
        return "?";
    }

    public static function provideParseValueData(): array
    {
        return [
            ["?", "?", " nicht beantwortet"]
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
                'solution' => null
            ],
            [
                "comparator" => "<>",
                "expected" => true,
                'index' => null,
                'has_solution' => true,
                'solution' => ['value' => true]
            ],
            [
                "comparator" => "==",
                "expected" => false,
                'index' => null,
                'has_solution' => true,
                'solution' => ['value' => true]
            ],
            [
                "comparator" => "=",
                "expected" => true,
                'index' => 1,
                'has_solution' => false,
                'solution' => null,
            ],
            [
                "comparator" => "<>",
                "expected" => true,
                'index' => 1,
                'has_solution' => true,
                'solution' => ['value' => true],
            ],
            [
                "comparator" => "==",
                "expected" => false,
                'index' => 1,
                'has_solution' => true,
                'solution' => ['value' => true],
            ],
        ];
    }
}

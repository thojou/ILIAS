<?php

class ilAssLacAnswerOfQuestionExpressionTest extends ilAssLacExpressionTestCase
{
    /**
     * @dataProvider provideParseValueData
     */
    public function test_parseValue(string $input_value, string $expected_value, string $expected_description): void
    {
        parent::test_parseValue($input_value, $expected_value, $expected_description);

        $this->assertIsInt($this->expression->getQuestionIndex());
    }

    public function test_QuestionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacQuestionExpressionInterface::class, $this->expression);
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacAnswerOfQuestionExpression::class;
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/(Q\d+)(?=\=|<|>|\s|$)/';
    }

    protected function getExpectedIdentifier(): string
    {
        return "Qn";
    }

    public static function provideParseValueData(): array
    {
        return [
            ['Q5', 'Q5', 'Frage 5 '],
            ['Q3', 'Q3', 'Frage 3 ']
        ];
    }
}

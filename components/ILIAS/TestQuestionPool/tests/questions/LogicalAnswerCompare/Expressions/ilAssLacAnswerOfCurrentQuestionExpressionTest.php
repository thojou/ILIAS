<?php

use PHPUnit\Framework\TestCase;

class ilAssLacAnswerOfCurrentQuestionExpressionTest extends ilAssLacExpressionTestCase
{
    public function test_QuestionExpressionInterfaceImplemented(): void
    {
        $this->assertInstanceOf(ilAssLacQuestionExpressionInterface::class, $this->expression);
        $this->assertNull($this->expression->getQuestionIndex());
    }

    protected function getExpressionClass(): string
    {
        return ilAssLacAnswerOfCurrentQuestionExpression::class;
    }

    protected function getExpectedStaticPattern(): string
    {
        return '/(R)(?=\=|<|>|\s|$)/';
    }

    protected function getExpectedIdentifier(): string
    {
        return "R";
    }

    public static function provideParseValueData(): array
    {
        return [
            ['R', 'R', 'Aktuelle Frage']
        ];
    }
}

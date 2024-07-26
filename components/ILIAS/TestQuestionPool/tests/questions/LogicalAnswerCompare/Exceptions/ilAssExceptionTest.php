<?php

use PHPUnit\Framework\TestCase;

class ilAssExceptionTest extends TestCase
{
    /**
     * @dataProvider provideExceptionData
     */
    public function test_exception_form_alert(string $exception_class, array $exceptions_args, string $expected_message): void
    {
        $exception = new $exception_class(...$exceptions_args);
        $this->assertInstanceOf(ilAssLacFormAlertProvider::class, $exception);
        $this->assertInstanceOf(ilAssLacException::class, $exception);

        $lng = $this->createMock(ilLanguage::class);
        $lng
            ->expects($this->atLeastOnce())
            ->method('txt')
            ->willReturn('test');

        $this->assertStringContainsString('test', $exception->getFormAlert($lng));
        $this->assertEquals($expected_message, $exception->getMessage());
    }

    public static function provideExceptionData(): array
    {
        return [
            [ilAssLacAnswerIndexNotExist::class, [1, 2], 'The Question with index "Q1" does not have an answer with the index "2" '],
            [ilAssLacAnswerIndexNotExist::class, [null, 2], 'The Current Question does not have an answer with the index "2"'],
            [ilAssLacAnswerValueNotExist::class, [1, 'val', 2], 'The value "val" does not exist for the question Q1[2]'],
            [ilAssLacAnswerValueNotExist::class, [null, 'val', 2], 'The value "val" does not exist for the answer with index "2" of the current question'],
            [ilAssLacAnswerValueNotExist::class, [1, 'val', null], 'The value "val" does not exist for the question Q1'],
            [ilAssLacAnswerValueNotExist::class, [null, 'val', null], 'The value "val" does not exist for the current question'],
            [ilAssLacConditionParserException::class, [23], 'The expression at position "23" is not valid'],
            [ilAssLacDuplicateElement::class, [5], 'Duplicate key "5" in condition'],
            [ilAssLacExpressionNotSupportedByQuestion::class, ["+42+", 1], 'The expression "+42+" is not supported by the question with index "Q1"'],
            [ilAssLacExpressionNotSupportedByQuestion::class, ["+42+", null], 'The expression "+42+" is not supported by the current question'],
            [ilAssLacMissingBracket::class, [")"], 'There is a bracket ")" missing in the condition'],
            [ilAssLacOperatorNotSupportedByExpression::class, ["~Hello World~", '>='], 'The expression "~Hello World~" is not supported by the operator ">="'],
            [ilAssLacQuestionNotExist::class, [5], 'The Question with index "Q5" does not exist'],
            [ilAssLacQuestionNotReachable::class, [1], 'The Question with index "Q1" is not reachable from this node'],
            [ilAssLacUnableToParseCondition::class, ["false"], 'The parser is unable to parse the condition "false"'],
            [ilAssLacUnsupportedExpression::class, [":2.2:"], 'The expression ":2.2:" is not supported'],
            [ilAssLacUnsupportedOperation::class, ["=="], 'The operator "==" is not supported'],
        ];
    }
}

<?php


use PHPUnit\Framework\MockObject\MockObject;

trait ilAssLacSolutionExpressionTestCaseTrait
{
    /**
     * @dataProvider provideCheckResultData
     */
    public function test_check_result(string $comparator, bool $expected, ?int $index = null, bool $has_solution = false, mixed $solution = null): void
    {
        $question_result_mock = $this->createQuestionResultMock($solution, $has_solution);

        $result = $this->expression->checkResult($question_result_mock, $comparator, $index);

        $this->assertEquals($expected, $result);
    }

    protected function createQuestionResultMock(mixed $solution, bool $has_solution = false): ilUserQuestionResult|MockObject
    {
        $question_result_mock = $this->createMock(ilUserQuestionResult::class);
        $question_result_mock
            ->expects($this->any())
            ->method('getSolutionForKey')
            ->willReturn($solution);
        $question_result_mock
            ->expects($this->any())
            ->method('getUserSolutionsByIdentifier')
            ->willReturn($solution ?: []);
        $question_result_mock
            ->expects($this->any())
            ->method('hasSolutions')
            ->willReturn($has_solution);
        $question_result_mock
            ->expects($this->any())
            ->method('getSolutions')
            ->willReturn($solution ? [$solution] : []);

        return $question_result_mock;
    }

    abstract public static function provideCheckResultData(): array;
}

<?php

use ILIAS\Refinery\IdentityTransformation;
use ILIAS\Refinery\Random\Group;
use PHPUnit\Framework\MockObject\MockObject;

class ilAssLacCompositeEvaluatorTest extends assBaseTestCase
{
    private ilAssLacCompositeEvaluator $evaluator;
    private ilAssLacQuestionProvider&MockObject $object_loader_mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->object_loader_mock = $this->createMock(ilAssLacQuestionProvider::class);
        $this->evaluator = new ilAssLacCompositeEvaluator($this->object_loader_mock, 1, 1);
    }

    public function test_instance(): void
    {
        $this->assertInstanceOf(ilAssLacCompositeEvaluator::class, $this->evaluator);
    }

    /**
     * @dataProvider provide_evaluate_data
     */
    public function test_evaluate(string $condition, bool $expected_result, mixed $available_answer_options, string $question_class): void
    {
        global $DIC;

        $question_mock = $this->createMock($question_class);

        $question_result = new ilUserQuestionResult($question_mock, 1, 1);
        $question_result->addKeyValue(1, 1);

        $shuffler = new IdentityTransformation();
        $random = $DIC->refinery()->random();
        $random
            ->expects($this->any())
            ->method('dontShuffle')
            ->willReturn($shuffler);

        $question_mock = $this->createMock($question_class);
        $question_mock->expects($this->any())
            ->method('getShuffler')
            ->willReturn($shuffler);
        $question_mock
            ->expects($this->once())
            ->method('getUserQuestionResult')
            ->willReturn($question_result);
        $question_mock
            ->expects($this->any())
            ->method('getAvailableAnswerOptions')
            ->willReturn($available_answer_options);

        $this->object_loader_mock
            ->expects($this->once())
            ->method('getQuestion')
            ->willReturn($question_mock);

        $composite = (new ilAssLacConditionParser())->parse($condition);
        $result = $this->evaluator->evaluate($composite);

        $this->assertEquals($expected_result, $result);
    }

    public static function provide_evaluate_data(): array
    {
        $cloze_gap_select = new assClozeGap(1);
        $cloze_gap_select->addItem(new assAnswerCloze("Hello World", 10));
        return [
//            [
//                "condition" => "Q1 = ~Hello World~",
//                "expected_result" => true,
//                "available_answer_options" => [],
//                'question_class' => TestQuestionMockAdapter::class
//            ],
            [
                "condition" => "Q1[1] = ~Hello World~",
                "expected_result" => true,
                "available_answer_options" => $cloze_gap_select,
                'question_class' => assClozeTest::class
            ],
            [
                "condition" => "Q1[1] = %100%",
                "expected_result" => true,
                "available_answer_options" => $cloze_gap_select,
                'question_class' => assClozeTest::class
            ],
        ];
    }

    public function test_evaluate_cloze_text(): void
    {
        global $DIC;

        $cloze_gap = new assClozeGap(0);
        $cloze_gap->addItem(new assAnswerCloze("Hello World", 10));
        $question_mock = $this->createQuestionMock(assClozeTest::class, $cloze_gap);

        $question_mock
            ->expects($this->once())
            ->method('getTextgapPoints')
            ->willReturn(7.2);

        $this->mockUserQuestionResult($question_mock, "Hello World");
        $this->mockShuffler($question_mock);
        $this->mockLoadQuestion($question_mock);

        $result = $this->evaluate_by_condition("Q1[1] = %72%");
        $this->assertTrue($result);
    }

    public function test_evaluate_cloze_numeric(): void
    {
        global $DIC;

        $cloze_gap = new assClozeGap(2);
        $cloze_gap->addItem(new assAnswerCloze(15, 10));
        $question_mock = $this->createQuestionMock(assClozeTest::class, $cloze_gap);

        $question_mock
            ->expects($this->once())
            ->method('getNumericgapPoints')
            ->willReturn(10.0);

        $this->mockUserQuestionResult($question_mock, 15);
        $this->mockShuffler($question_mock);
        $this->mockLoadQuestion($question_mock);

        $result = $this->evaluate_by_condition("Q1[1] = %100%");
        $this->assertTrue($result);
    }

    public function test_evaluate_formula(): void
    {
        $formula_result = $this->createMock(assFormulaQuestionResult::class);
        $formula_result
            ->expects($this->once())
            ->method('getReachedPoints')
            ->willReturn(10.0);
        $formula_result
            ->expects($this->once())
            ->method('getPoints')
            ->willReturn(10.0);

        $question_mock = $this->createQuestionMock(assFormulaQuestion::class, $formula_result);

        $question_result = $this->mockUserQuestionResult($question_mock, 100);
        $this->mockShuffler($question_mock);
        $this->mockLoadQuestion($question_mock);

        $question_result->addKeyValue('1_unit', 1);

        $result = $this->evaluate_by_condition("R[1] = %100%");
        $this->assertTrue($result);
    }

    public function test_evaluate_complex_term(): void
    {
        $question_mock = $this->createQuestionMock(TestQuestionMockAdapter::class, []);

        $this->mockUserQuestionResult($question_mock, "Hello World");
        $this->mockShuffler($question_mock);
        $this->mockLoadQuestion($question_mock);

        $result = $this->evaluate_by_condition("Q1 = ~Hello World~ & R = ~Hello World~");
        $this->assertTrue($result);

        $result = $this->evaluate_by_condition("Q1 = ~Hello World~ | R = ~Hello Universe~");
        $this->assertTrue($result);


        $result = $this->evaluate_by_condition("!(Q1 = ~Hello World~ & R = ~Hello Universe~)");
        $this->assertTrue($result);
    }

    private function evaluate_by_condition(string $condition): bool
    {
        $composite = (new ilAssLacConditionParser())->parse($condition);
        $result = $this->evaluator->evaluate($composite);

        return $result;
    }

    private function mockShuffler(assQuestion|MockObject $question_mock): void
    {
        global $DIC;

        $shuffler = new IdentityTransformation();
        /** @var Group|MockObject $random */
        $random = $DIC->refinery()->random();
        $random
            ->expects($this->any())
            ->method('dontShuffle')
            ->willReturn($shuffler);

        $question_mock->expects($this->any())
            ->method('getShuffler')
            ->willReturn($shuffler);
    }

    private function createQuestionMock(string $question_class, mixed $available_answer_options = null): assQuestion|MockObject
    {
        $question_mock = $this->createMock($question_class);
        $question_mock
            ->expects($this->any())
            ->method('getAvailableAnswerOptions')
            ->willReturn($available_answer_options);

        return $question_mock;
    }

    private function mockUserQuestionResult(assQuestion|MockObject $question_mock, mixed $value, float $reach_percentage = 100.): ilUserQuestionResult
    {
        $question_result = new ilUserQuestionResult($question_mock, 1, 1);
        $question_result->setReachedPercentage($reach_percentage);
        $question_result->addKeyValue(1, $value);

        $question_mock
            ->expects($this->atLeastOnce())
            ->method('getUserQuestionResult')
            ->willReturn($question_result);

        return $question_result;
    }

    private function mockLoadQuestion($question_mock): void
    {
        $this->object_loader_mock
            ->expects($this->atLeastOnce())
            ->method('getQuestion')
            ->willReturn($question_mock);
    }

}

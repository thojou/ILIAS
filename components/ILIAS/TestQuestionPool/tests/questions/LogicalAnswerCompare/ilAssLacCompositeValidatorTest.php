<?php

use ILIAS\Refinery\IdentityTransformation;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function Sabre\Uri\parse;

class ilAssLacCompositeValidatorTest extends assBaseTestCase
{
    private ilAssLacCompositeValidator $validator;
    private ilAssLacQuestionProvider&MockObject $object_loader_mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->object_loader_mock = $this->createMock(ilAssLacQuestionProvider::class);
        $this->validator = new ilAssLacCompositeValidator($this->object_loader_mock);
    }

    public function test_instance(): void
    {
        $this->assertInstanceOf(ilAssLacCompositeValidator::class, $this->validator);
    }

    /**
     * @dataProvider provideValidateData
     */
    public function test_validate(
        string $condition,
        mixed $available_answer_options,
        array $expression_types,
        array $operators,
        string $question_class = TestQuestionMockAdapter::class
    ): void {
        global $DIC;

        /** @var MockObject $random */
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

        $this->object_loader_mock
            ->expects($this->once())
            ->method('getQuestion')
            ->willReturn($question_mock);

        $question_mock
            ->expects($this->any())
            ->method('getAvailableAnswerOptions')
            ->willReturn($available_answer_options);

        $question_mock
            ->expects($this->once())
            ->method('getExpressionTypes')
            ->willReturn($expression_types);

        $question_mock
            ->expects($this->once())
            ->method('getOperators')
            ->willReturn($operators);

        $composite = (new ilAssLacConditionParser())->parse($condition);
        $this->validator->validate($composite);
    }

    public static function provideValidateData(): array
    {
        $cloze_gap_text = new assClozeGap(0);
        $cloze_gap_select = new assClozeGap(1);
        $cloze_gap_select->addItem(new assAnswerCloze("unmatching", order: 1));
        $cloze_gap_select->addItem(new assAnswerCloze("matching", order: 2));
        $cloze_gap_numeric = new assClozeGap(2);

        return [
            [
                "condition" => "Q1 = +2+",
                "available_answer_options" => ['1', '2'],
                "expression_types" => [iQuestionCondition::NumberOfResultExpression],
                "operators" => ["="]
            ],
            [
                "condition" => "R = +2+",
                "available_answer_options" => ['1', '2'],
                "expression_types" => [iQuestionCondition::NumberOfResultExpression],
                "operators" => ["="]
            ],
            [
                "condition" => "Q1[3] >= #42#",
                "available_answer_options" => ['1', '2', '3'],
                "expression_types" => [iQuestionCondition::NumericResultExpression],
                "operators" => [">="]
            ],
            [
                "condition" => "R[3] >= #42#",
                "available_answer_options" => ['1', '2', '3'],
                "expression_types" => [iQuestionCondition::NumericResultExpression],
                "operators" => [">="]
            ],
            [
                "condition" => "Q1 = $2,1,3,4$",
                "available_answer_options" => ['1', '2', '3', '4'],
                "expression_types" => [iQuestionCondition::OrderingResultExpression],
                "operators" => ["="]
            ],
            [
                "condition" => "Q1[2] = ~matching~",
                "available_answer_options" => $cloze_gap_text,
                "expression_types" => [iQuestionCondition::StringResultExpression],
                "operators" => ["="],
                "question_class" => assClozeTest::class
            ],
            [
                "condition" => "Q1[2] = ~matching~",
                "available_answer_options" => $cloze_gap_select,
                "expression_types" => [iQuestionCondition::StringResultExpression],
                "operators" => ["="],
                "question_class" => assClozeTest::class
            ],
            [
                "condition" => "Q1[2] = +2+",
                "available_answer_options" => $cloze_gap_select,
                "expression_types" => [iQuestionCondition::NumberOfResultExpression],
                "operators" => ["="],
                "question_class" => assClozeTest::class
            ],
            [
                "condition" => "Q1[2] = #42#",
                "available_answer_options" => $cloze_gap_numeric,
                "expression_types" => [iQuestionCondition::NumericResultExpression],
                "operators" => ["="],
                "question_class" => assClozeTest::class
            ],
            [
                "condition" => "Q1[2] = ?",
                "available_answer_options" => $cloze_gap_text,
                "expression_types" => [iQuestionCondition::EmptyAnswerExpression],
                "operators" => ["="],
                "question_class" => assClozeTest::class
            ],
            [
                "condition" => "Q1 = %70%",
                "available_answer_options" => $cloze_gap_text,
                "expression_types" => [iQuestionCondition::PercentageResultExpression],
                "operators" => ["="],
                "question_class" => assClozeTest::class
            ]
        ];
    }
    /**
     * @dataProvider provideValidateExceptionData
     */
    public function test_validate_exception(
        string $condition,
        mixed $available_answer_options,
        array $expression_types,
        array $operators,
        string $question_class = TestQuestionMockAdapter::class,
        string $expected_exception = Exception::class
    ): void {
        global $DIC;

        $this->expectException($expected_exception);

        /** @var MockObject $random */
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

        $this->object_loader_mock
            ->expects($this->once())
            ->method('getQuestion')
            ->willReturn($question_mock);

        $question_mock
            ->expects($this->any())
            ->method('getAvailableAnswerOptions')
            ->willReturn($available_answer_options);

        $question_mock
            ->expects($this->any())
            ->method('getExpressionTypes')
            ->willReturn($expression_types);

        $question_mock
            ->expects($this->any())
            ->method('getOperators')
            ->willReturn($operators);

        $composite = (new ilAssLacConditionParser())->parse($condition);
        $this->validator->validate($composite);
    }

    public static function provideValidateExceptionData(): array
    {
        return [
            [
                "condition" => "Q1 = $2,2,1,3,4$",
                "available_answer_options" => ['1', '2', '3', '4'],
                "expression_types" => [iQuestionCondition::OrderingResultExpression],
                "operators" => ["="],
                "question_class" => TestQuestionMockAdapter::class,
                "expected_exception" => ilAssLacDuplicateElement::class
            ],
            [
                "condition" => "Q1[2] = #42#",
                "available_answer_options" => new assClozeGap(1),
                "expression_types" => [],
                "operators" => ["="],
                "question_class" => TestQuestionMockAdapter::class,
                "expected_exception" => ilAssLacExpressionNotSupportedByQuestion::class
            ],
            [
                "condition" => "Q1 >= ~Hello World~",
                "available_answer_options" => [],
                "expression_types" => [iQuestionCondition::StringResultExpression],
                "operators" => ["="],
                "question_class" => TestQuestionMockAdapter::class,
                "expected_exception" => ilAssLacOperatorNotSupportedByExpression::class
            ],
            [
                "condition" => "Q1[2] = #42#",
                "available_answer_options" => null,
                "expression_types" => [iQuestionCondition::NumericResultExpression],
                "operators" => ["="],
                "question_class" => TestQuestionMockAdapter::class,
                "expected_exception" => ilAssLacAnswerIndexNotExist::class
            ],
            [
                "condition" => "R[2] = %42%",
                "available_answer_options" => ["2"],
                "expression_types" => [iQuestionCondition::PercentageResultExpression],
                "operators" => ["="],
                "question_class" => TestQuestionMockAdapter::class,
                "expected_exception" => ilAssLacExpressionNotSupportedByQuestion::class

            ],
            [
                "condition" => "Q1[2] = #42#",
                "available_answer_options" => new assClozeGap(1),
                "expression_types" => [iQuestionCondition::NumericResultExpression],
                "operators" => ["="],
                "question_class" => assClozeTest::class,
                "expected_exception" => ilAssLacAnswerValueNotExist::class
            ]
        ];
    }

    public function test_question_not_exists(): void
    {
        $this->expectException(ilAssLacQuestionNotExist::class);

        $this->object_loader_mock
            ->expects($this->once())
            ->method('getQuestion')
            ->willReturn(null);

        $composite = (new ilAssLacConditionParser())->parse("Q1 = +2+");
        $this->validator->validate($composite);
    }

    /**
     * @dataProvider provide_unable_to_parse_condition_data
     */
    public function test_unable_to_parse_condition(string $left_node_class, string $right_node_class): void
    {
        $this->expectException(ilAssLacUnableToParseCondition::class);

        $composite = $this->createMock(ilAssLacAbstractComposite::class);
        $composite
            ->expects($this->any())
            ->method('addNode')
            ->willReturnCallback(function ($node) use ($composite) {
                $composite->nodes[] = $node;
            });

        $composite->addNode($this->createMock($left_node_class));
        $composite->addNode($this->createMock($right_node_class));

        $this->validator->validate($composite);
    }

    public static function provide_unable_to_parse_condition_data(): array
    {
        return [
            [ilAssLacAbstractOperation::class, ilAssLacAbstractExpression::class],
            [ilAssLacAbstractExpression::class, ilAssLacAbstractOperation::class]
        ];
    }

}

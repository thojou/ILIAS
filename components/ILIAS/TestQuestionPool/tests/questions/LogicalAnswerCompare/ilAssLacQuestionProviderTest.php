<?php

use ILIAS\TestQuestionPool\QuestionPoolDIC;
use ILIAS\TestQuestionPool\Questions\GeneralQuestionProperties;
use ILIAS\TestQuestionPool\Questions\GeneralQuestionPropertiesRepository;
use PHPUnit\Framework\MockObject\MockObject;

class ilAssLacQuestionProviderTest extends assBaseTestCase
{
    private ilAssLacQuestionProvider $provider;
    private assQuestion&MockObject $questionMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = new ilAssLacQuestionProvider();
        $this->questionMock = $this->createMock(TestQuestionMockAdapter::class);
        TestQuestionMockAdapter::setMockObject($this->questionMock);
    }

    public function test_setQuestion()
    {
        $question_condition_mock = $this->createMock(TestQuestionMockAdapter::class);
        $this->provider->setQuestion($question_condition_mock);
        $this->assertEquals($question_condition_mock, $this->provider->getQuestion());
    }

    public function test_setQuestionId(): void
    {
        $qpl_dic = QuestionPoolDIC::dic();
        $question_properties = $this->createMock(GeneralQuestionProperties::class);
        $question_repository = $this->createMock(GeneralQuestionPropertiesRepository::class);
        $question_repository->expects($this->once())
            ->method('getForQuestionId')
            ->with(1)
            ->willReturn($question_properties);

        $question_properties
            ->expects($this->once())
            ->method('getClassName')
            ->willReturn('TestQuestionMockAdapter');

        $this->questionMock
            ->expects($this->once())
            ->method('loadFromDb');

        $qpl_dic['question.general_properties.repository'] = $question_repository;

        $this->provider->setQuestionId(1);
        $this->assertInstanceOf(iQuestionCondition::class, $this->provider->getQuestion());
    }
}

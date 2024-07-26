<?php

use ILIAS\UI\Component\Modal\Modal;

class ilAssLacLegendGUITest extends assBaseTestCase
{
    private ilGlobalTemplateInterface $global_page_template;
    private \ILIAS\UI\Factory $ui_factory;
    private ilLanguage $lng;
    private ilAssLacLegendGUI $gui;

    protected function setUp(): void
    {
        parent::setUp();

        global $DIC;

        $this->global_page_template = $DIC->ui()->mainTemplate();
        $this->ui_factory = $DIC->ui()->factory();
        $this->lng = $DIC->language();
        $this->gui = new ilAssLacLegendGUI(
            $this->global_page_template,
            $this->lng,
            $this->ui_factory
        );
    }

    public function test_instance(): void
    {
        $this->assertInstanceOf(ilAssLacLegendGUI::class, $this->gui);
    }

    public function test_question_object(): void
    {
        $this->assertNull($this->gui->getQuestionOBJ());

        $question_obj = $this->createMock(TestQuestionMockAdapter::class);
        $this->gui->setQuestionOBJ($question_obj);

        $this->assertInstanceOf(assQuestion::class, $this->gui->getQuestionOBJ());
        $this->assertInstanceOf(iQuestionCondition::class, $this->gui->getQuestionOBJ());
    }

    public function test_get(): void
    {
        $question_obj = $this->createMock(TestQuestionMockAdapter::class);
        $question_obj
            ->expects($this->once())
            ->method('getExpressionTypes')
            ->willReturn([iQuestionCondition::PercentageResultExpression]);
        $this->gui->setQuestionOBJ($question_obj);

        $this->assertInstanceOf(Modal::class, $this->gui->get());
    }
}

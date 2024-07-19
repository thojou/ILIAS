<?php

use PHPUnit\Framework\MockObject\MockObject;

/**
 * @property MockObject&ilAssQuestionFeedback $mockObject
 */
class TestQuestionMockAdapterFeedback extends ilAssQuestionFeedback
{
    use TestMockAdapterTrait;

    public function getSpecificAnswerFeedbackTestPresentation(
        int $question_id,
        int $question_index,
        int $answer_index
    ): string {
        return self::$mockObject->getSpecificAnswerFeedbackTestPresentation($question_id, $question_index, $answer_index);
    }

    public function completeSpecificFormProperties(ilPropertyFormGUI $form): void
    {
        self::$mockObject->completeSpecificFormProperties($form);
    }

    public function initSpecificFormProperties(ilPropertyFormGUI $form): void
    {
        self::$mockObject->initSpecificFormProperties($form);
    }

    public function saveSpecificFormProperties(ilPropertyFormGUI $form): void
    {
        self::$mockObject->saveSpecificFormProperties($form);
    }

    public function getSpecificAnswerFeedbackContent(int $question_id, int $question_index, int $answer_index): string
    {
        return self::$mockObject->getSpecificAnswerFeedbackContent($question_id, $question_index, $answer_index);
    }

    public function getAllSpecificAnswerFeedbackContents(int $question_id): string
    {
        return self::$mockObject->getAllSpecificAnswerFeedbackContents($question_id);
    }

    public function saveSpecificAnswerFeedbackContent(
        int $question_id,
        int $question_index,
        int $answer_index,
        string $feedback_content
    ): int {
        return self::$mockObject->saveSpecificAnswerFeedbackContent($question_id, $question_index, $answer_index, $feedback_content);
    }

    public function deleteSpecificAnswerFeedbacks(
        int $question_id,
        bool $isAdditionalContentEditingModePageObject
    ): void {
        self::$mockObject->deleteSpecificAnswerFeedbacks($question_id, $isAdditionalContentEditingModePageObject);
    }

    protected function cloneSpecificFeedback(int $originalQuestionId, int $duplicateQuestionId): void
    {
        self::$mockObject->cloneSpecificFeedback($originalQuestionId, $duplicateQuestionId);
    }

    protected function isSpecificAnswerFeedbackId(int $feedbackId): bool
    {
        return self::$mockObject->isSpecificAnswerFeedbackId($feedbackId);
    }

    public function getSpecificAnswerFeedbackExportPresentation(
        int $question_id,
        int $question_index,
        int $answer_index
    ): string {
        return self::$mockObject->getSpecificAnswerFeedbackExportPresentation($question_id, $question_index, $answer_index);
    }

    public function importSpecificAnswerFeedback(
        int $question_id,
        int $question_index,
        int $answer_index,
        string $feedback_content
    ): void {
        self::$mockObject->importSpecificAnswerFeedback($question_id, $question_index, $answer_index, $feedback_content);
    }
}

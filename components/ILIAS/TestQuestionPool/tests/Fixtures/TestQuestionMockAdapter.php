<?php

use ILIAS\Notes\InternalDataService as NotesInternalDataService;
use ILIAS\Notes\NoteDBRepository as NotesRepo;
use ILIAS\Notes\NotesManager;
use ILIAS\Refinery\Transformation;
use ILIAS\Test\Logging\AdditionalInformationGenerator;
use ILIAS\Test\Logging\TestParticipantInteraction;
use ILIAS\Test\Logging\TestParticipantInteractionTypes;
use ILIAS\Test\Logging\TestQuestionAdministrationInteraction;
use ILIAS\Test\Logging\TestQuestionAdministrationInteractionTypes;
use ILIAS\TestQuestionPool\Questions\SuggestedSolution\SuggestedSolution;
use ILIAS\TestQuestionPool\Questions\SuggestedSolution\SuggestedSolutionsDatabaseRepository;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @property MockObject|assQuestion $mockObject
 */
class TestQuestionMockAdapter extends assQuestion implements iQuestionCondition
{
    use TestMockAdapterTrait;

    public function __construct(
    ) {
        parent::__construct();
    }

    public function getQuestionType(): string
    {
        return self::$mockObject->getQuestionType();
    }

    public function isComplete(): bool
    {
        return self::$mockObject->isComplete();
    }

    public function saveWorkingData(int $active_id, ?int $pass = null, bool $authorized = true): bool
    {
        return self::$mockObject->saveWorkingData($active_id, $pass, $authorized);
    }

    public function calculateReachedPoints(int $active_id, ?int $pass = null, bool $authorized_solution = true): float
    {
        return self::$mockObject->calculateReachedPoints($active_id, $pass, $authorized_solution);
    }

    public function getAdditionalTableName(): string
    {
        return self::$mockObject->getAdditionalTableName();
    }

    public function getAnswerTableName(): string|array
    {
        return self::$mockObject->getAnswerTableName();
    }

    public function toLog(AdditionalInformationGenerator $additional_info): array
    {
        return self::$mockObject->toLog($additional_info);
    }

    public function solutionValuesToLog(
        AdditionalInformationGenerator $additional_info,
        array $solution_values
    ): array|string {
        return self::$mockObject->solutionValuesToLog($additional_info, $solution_values);
    }

    public static function setForcePassResultUpdateEnabled(bool $force_pass_results_update_enabled): void
    {
        self::$mockObject->setForcePassResultUpdateEnabled($force_pass_results_update_enabled);
    }

    public static function isForcePassResultUpdateEnabled(): bool
    {
        return self::$mockObject->isForcePassResultUpdateEnabled();
    }

    protected function getQuestionAction(): string
    {
        return self::$mockObject->getQuestionAction();
    }

    protected function isNonEmptyItemListPostSubmission(string $postSubmissionFieldname): bool
    {
        return self::$mockObject->isNonEmptyItemListPostSubmission($postSubmissionFieldname);
    }

    public function getCurrentUser(): ilObjUser
    {
        return self::$mockObject->getCurrentUser();
    }

    public function getShuffler(): Transformation
    {
        return self::$mockObject->getShuffler();
    }

    public function setShuffler(Transformation $shuffler): void
    {
        self::$mockObject->setShuffler($shuffler);
    }

    public function setProcessLocker(ilAssQuestionProcessLocker $processLocker): void
    {
        self::$mockObject->setProcessLocker($processLocker);
    }

    public function getProcessLocker(): ilAssQuestionProcessLocker
    {
        return self::$mockObject->getProcessLocker();
    }

    public function setTitle(string $title = ""): void
    {
        self::$mockObject->setTitle($title);
    }

    public function setId(int $id = -1): void
    {
        self::$mockObject->setId($id);
    }

    public function setTestId(int $id = -1): void
    {
        self::$mockObject->setTestId($id);
    }

    public function setComment(string $comment = ""): void
    {
        self::$mockObject->setComment($comment);
    }

    public function setShuffle(?bool $shuffle = true): void
    {
        self::$mockObject->setShuffle($shuffle);
    }

    public function setAuthor(string $author = ""): void
    {
        self::$mockObject->setAuthor($author);
    }

    public function setOwner(int $owner = -1): void
    {
        self::$mockObject->setOwner($owner);
    }

    public function getTitle(): string
    {
        return self::$mockObject->getTitle();
    }

    public function getTitleForHTMLOutput(): string
    {
        return self::$mockObject->getTitleForHTMLOutput();
    }

    public function getTitleFilenameCompliant(): string
    {
        return self::$mockObject->getTitleFilenameCompliant();
    }

    public function getId(): int
    {
        return self::$mockObject->getId();
    }

    public function getShuffle(): bool
    {
        return self::$mockObject->getShuffle();
    }

    public function getTestId(): int
    {
        return self::$mockObject->getTestId();
    }

    public function getComment(): string
    {
        return self::$mockObject->getComment();
    }

    public function getDescriptionForHTMLOutput(): string
    {
        return self::$mockObject->getDescriptionForHTMLOutput();
    }

    public function getThumbSize(): int
    {
        return self::$mockObject->getThumbSize();
    }

    public function setThumbSize(int $a_size): void
    {
        self::$mockObject->setThumbSize($a_size);
    }

    public function getMinimumThumbSize(): int
    {
        return self::$mockObject->getMinimumThumbSize();
    }

    public function getAuthor(): string
    {
        return self::$mockObject->getAuthor();
    }

    public function getAuthorForHTMLOutput(): string
    {
        return self::$mockObject->getAuthorForHTMLOutput();
    }

    public function getOwner(): int
    {
        return self::$mockObject->getOwner();
    }

    public function getObjId(): int
    {
        return self::$mockObject->getObjId();
    }

    public function setObjId(int $obj_id = 0): void
    {
        self::$mockObject->setObjId($obj_id);
    }

    public function getLifecycle(): ilAssQuestionLifecycle
    {
        return self::$mockObject->getLifecycle();
    }

    public function setLifecycle(ilAssQuestionLifecycle $lifecycle): void
    {
        self::$mockObject->setLifecycle($lifecycle);
    }

    public function setExternalId(?string $external_id): void
    {
        self::$mockObject->setExternalId($external_id);
    }

    public function getExternalId(): string
    {
        return self::$mockObject->getExternalId();
    }

    public static function _getSuggestedSolutionOutput(int $question_id): string
    {
        return self::$mockObject->_getSuggestedSolutionOutput($question_id);
    }

    public function getSuggestedSolutionOutput(): string
    {
        return self::$mockObject->getSuggestedSolutionOutput();
    }

    public function getSuggestedSolutions(): array
    {
        return self::$mockObject->getSuggestedSolutions();
    }

    public static function _getReachedPoints(int $active_id, int $question_id, int $pass): float
    {
        return self::$mockObject->_getReachedPoints($active_id, $question_id, $pass);
    }

    public function getReachedPoints(int $active_id, int $pass): float
    {
        return self::$mockObject->getReachedPoints($active_id, $pass);
    }

    public function getMaximumPoints(): float
    {
        return self::$mockObject->getMaximumPoints();
    }

    public function validateSolutionSubmit(): bool
    {
        return self::$mockObject->validateSolutionSubmit();
    }

    protected function savePreviewData(ilAssQuestionPreviewSession $preview_session): void
    {
        self::$mockObject->savePreviewData($preview_session);
    }

    public function getSuggestedSolutionPath(): string
    {
        return self::$mockObject->getSuggestedSolutionPath();
    }

    public function getImagePath($question_id = null, $object_id = null): string
    {
        return self::$mockObject->getImagePath($question_id, $object_id);
    }

    public function getSuggestedSolutionPathWeb(): string
    {
        return self::$mockObject->getSuggestedSolutionPathWeb();
    }

    public function getImagePathWeb(): string
    {
        return self::$mockObject->getImagePathWeb();
    }

    public function getTestOutputSolutions(int $activeId, int $pass): array
    {
        return self::$mockObject->getTestOutputSolutions($activeId, $pass);
    }

    public function getUserSolutionPreferingIntermediate(int $active_id, ?int $pass = null): array
    {
        return self::$mockObject->getUserSolutionPreferingIntermediate($active_id, $pass);
    }

    public function getSolutionValues(int $active_id, ?int $pass = null, bool $authorized = true): array
    {
        return self::$mockObject->getSolutionValues($active_id, $pass, $authorized);
    }

    public function deleteAnswers(int $question_id): void
    {
        self::$mockObject->deleteAnswers($question_id);
    }

    public function deleteAdditionalTableData(int $question_id): void
    {
        self::$mockObject->deleteAdditionalTableData($question_id);
    }

    protected function deletePageOfQuestion(int $question_id): void
    {
        self::$mockObject->deletePageOfQuestion($question_id);
    }

    public function delete(int $question_id): void
    {
        self::$mockObject->delete($question_id);
    }

    public function getTotalAnswers(): int
    {
        return self::$mockObject->getTotalAnswers();
    }

    public static function isFileAvailable(string $file): bool
    {
        return self::$mockObject->isFileAvailable($file);
    }

    public function cloneXHTMLMediaObjectsOfQuestion(int $source_question_id): void
    {
        self::$mockObject->cloneXHTMLMediaObjectsOfQuestion($source_question_id);
    }

    public function createPageObject(): void
    {
        self::$mockObject->createPageObject();
    }

    public function clonePageOfQuestion(int $a_q_id): void
    {
        self::$mockObject->clonePageOfQuestion($a_q_id);
    }

    public function getPageOfQuestion(): string
    {
        return self::$mockObject->getPageOfQuestion();
    }

    public function setOriginalId(?int $original_id): void
    {
        self::$mockObject->setOriginalId($original_id);
    }

    public function getOriginalId(): ?int
    {
        return self::$mockObject->getOriginalId();
    }

    public function fixSvgToPng(string $imageFilenameContainingString): string
    {
        return self::$mockObject->fixSvgToPng($imageFilenameContainingString);
    }

    public function fixUnavailableSkinImageSources(string $html): string
    {
        return self::$mockObject->fixUnavailableSkinImageSources($html);
    }

    public function loadFromDb(int $question_id): void
    {
        self::$mockObject->loadFromDb($question_id);
    }

    public function createNewQuestion(bool $a_create_page = true): int
    {
        return self::$mockObject->createNewQuestion($a_create_page);
    }

    public function saveQuestionDataToDb(?int $original_id = null): void
    {
        self::$mockObject->saveQuestionDataToDb($original_id);
    }

    public function duplicate(
        bool $for_test = true,
        string $title = '',
        string $author = '',
        int $owner = -1,
        $test_obj_id = null
    ): int {
        return self::$mockObject->duplicate(
            $for_test,
            $title,
            $author,
            $owner,
            $test_obj_id
        );
    }

    protected function cloneQuestionTypeSpecificProperties(assQuestion $target): assQuestion
    {
        return self::$mockObject->cloneQuestionTypeSpecificProperties($target);
    }

    public function saveToDb(?int $original_id = null): void
    {
        self::$mockObject->saveToDb($original_id);
    }

    protected function removeAllImageFiles(string $image_target_path): void
    {
        self::$mockObject->removeAllImageFiles($image_target_path);
    }

    public static function saveOriginalId(int $questionId, int $originalId): void
    {
        self::$mockObject->saveOriginalId($questionId, $originalId);
    }

    public static function resetOriginalId(int $questionId): void
    {
        self::$mockObject->resetOriginalId($questionId);
    }

    protected function onDuplicate(
        int $original_parent_id,
        int $original_question_id,
        int $duplicate_parent_id,
        int $duplicate_question_id
    ): void {
        self::$mockObject->onDuplicate(
            $original_parent_id,
            $original_question_id,
            $duplicate_parent_id,
            $duplicate_question_id
        );
    }

    protected function afterSyncWithOriginal(
        int $original_question_id,
        int $clone_question_id,
        int $original_parent_id,
        int $clone_parent_id
    ): void {
        self::$mockObject->afterSyncWithOriginal(
            $original_question_id,
            $clone_question_id,
            $original_parent_id,
            $clone_parent_id
        );
    }

    protected function onCopy(
        int $sourceParentId,
        int $sourceQuestionId,
        int $targetParentId,
        int $targetQuestionId
    ): void {
        self::$mockObject->onCopy(
            $sourceParentId,
            $sourceQuestionId,
            $targetParentId,
            $targetQuestionId
        );
    }

    protected function duplicateComments(
        int $parent_source_id,
        int $source_id,
        int $parent_target_id,
        int $target_id
    ): void {
        self::$mockObject->duplicateComments(
            $parent_source_id,
            $source_id,
            $parent_target_id,
            $target_id
        );
    }

    protected function deleteComments(): void
    {
        self::$mockObject->deleteComments();
    }

    protected function getNotesManager(): NotesManager
    {
        return self::$mockObject->getNotesManager();
    }

    protected function getNotesDataService(): NotesInternalDataService
    {
        return self::$mockObject->getNotesDataService();
    }

    protected function getNotesRepo(): NotesRepo
    {
        return self::$mockObject->getNotesRepo();
    }

    public function deleteSuggestedSolutions(): void
    {
        self::$mockObject->deleteSuggestedSolutions();
    }

    public function getSuggestedSolution(int $subquestion_index = 0): ?SuggestedSolution
    {
        return self::$mockObject->getSuggestedSolution($subquestion_index);
    }

    protected function cloneSuggestedSolutions(int $source_question_id, int $target_question_id): void
    {
        self::$mockObject->cloneSuggestedSolutions(
            $source_question_id,
            $target_question_id
        );
    }

    protected function duplicateSuggestedSolutionFiles(int $parent_id, int $question_id): void
    {
        self::$mockObject->duplicateSuggestedSolutionFiles($parent_id, $question_id);
    }

    protected function cloneSuggestedSolutionFiles(int $source_question_id, int $target_question_id): void
    {
        self::$mockObject->cloneSuggestedSolutionFiles(
            $source_question_id,
            $target_question_id
        );
    }

    public function resolveInternalLink(string $internal_link): string
    {
        return self::$mockObject->resolveInternalLink($internal_link);
    }

    public function resolveSuggestedSolutionLinks(): void
    {
        self::$mockObject->resolveSuggestedSolutionLinks();
    }

    public function getInternalLinkHref(string $target): string
    {
        return self::$mockObject->getInternalLinkHref($target);
    }

    public function syncWithOriginal(): void
    {
        self::$mockObject->syncWithOriginal();
    }

    public static function instantiateQuestion(int $question_id): assQuestion
    {
        return self::$mockObject->instantiateQuestion($question_id);
    }

    public function getPoints(): float
    {
        return self::$mockObject->getPoints();
    }

    public function setPoints(float $points): void
    {
        self::$mockObject->setPoints($points);
    }

    public function getSolutionMaxPass(int $active_id): ?int
    {
        return self::$mockObject->getSolutionMaxPass($active_id);
    }

    public static function _getSolutionMaxPass(int $question_id, int $active_id): ?int
    {
        return self::$mockObject->_getSolutionMaxPass($question_id, $active_id);
    }

    public function isWriteable(): bool
    {
        return self::$mockObject->isWriteable();
    }

    public function deductHintPointsFromReachedPoints(
        ilAssQuestionPreviewSession $preview_session,
        $reached_points
    ): ?float {
        return self::$mockObject->deductHintPointsFromReachedPoints(
            $preview_session,
            $reached_points
        );
    }

    public function calculateReachedPointsFromPreviewSession(ilAssQuestionPreviewSession $preview_session)
    {
        return self::$mockObject->calculateReachedPointsFromPreviewSession($preview_session);
    }

    protected function ensureNonNegativePoints(float $points): float
    {
        return self::$mockObject->ensureNonNegativePoints($points);
    }

    public function isPreviewSolutionCorrect(ilAssQuestionPreviewSession $preview_session): bool
    {
        return self::$mockObject->isPreviewSolutionCorrect($preview_session);
    }

    public function buildHashedImageFilename(string $plain_image_filename, bool $unique = false): string
    {
        return self::$mockObject->buildHashedImageFilename($plain_image_filename, $unique);
    }

    public static function _setReachedPoints(
        int $active_id,
        int $question_id,
        float $points,
        float $maxpoints,
        int $pass,
        bool $manualscoring,
        bool $obligationsEnabled
    ): bool {
        return self::$mockObject->_setReachedPoints(
            $active_id,
            $question_id,
            $points,
            $maxpoints,
            $pass,
            $manualscoring,
            $obligationsEnabled
        );
    }

    public function getQuestion(): string
    {
        return self::$mockObject->getQuestion();
    }

    public function getQuestionForHTMLOutput(): string
    {
        return self::$mockObject->getQuestionForHTMLOutput();
    }

    protected function purifyAndPrepareTextAreaOutput(string $content): string
    {
        return self::$mockObject->purifyAndPrepareTextAreaOutput($content);
    }

    public function setQuestion(string $question = ""): void
    {
        self::$mockObject->setQuestion($question);
    }

    public function getQuestionTypeID(): int
    {
        return self::$mockObject->getQuestionTypeID();
    }

    public function cloneHints(int $source_question_id, int $target_question_id): void
    {
        self::$mockObject->cloneHints($source_question_id, $target_question_id);
    }

    protected function getRTETextWithMediaObjects(): string
    {
        return self::$mockObject->getRTETextWithMediaObjects();
    }

    public function cleanupMediaObjectUsage(): void
    {
        self::$mockObject->cleanupMediaObjectUsage();
    }

    public function getInstances(): array
    {
        return self::$mockObject->getInstances();
    }

    public static function _needsManualScoring(int $question_id): bool
    {
        return self::$mockObject->_needsManualScoring($question_id);
    }

    public function getActiveUserData(int $active_id): array
    {
        return self::$mockObject->getActiveUserData($active_id);
    }

    public function hasSpecificFeedback(): bool
    {
        return self::$mockObject->hasSpecificFeedback();
    }

    public static function getFeedbackClassNameByQuestionType(string $questionType): string
    {
        return self::$mockObject->getFeedbackClassNameByQuestionType($questionType);
    }

    public static function instantiateQuestionGUI(int $question_id): ?assQuestionGUI
    {
        return self::$mockObject->instantiateQuestionGUI($question_id);
    }

    public function setExportDetailsXLSX(
        ilAssExcelFormatHelper $worksheet,
        int $startrow,
        int $col,
        int $active_id,
        int $pass
    ): int {
        return self::$mockObject->setExportDetailsXLSX(
            $worksheet,
            $startrow,
            $col,
            $active_id,
            $pass
        );
    }

    public function getNrOfTries(): int
    {
        return self::$mockObject->getNrOfTries();
    }

    public function setNrOfTries(int $a_nr_of_tries): void
    {
        self::$mockObject->setNrOfTries($a_nr_of_tries);
    }

    public function setExportImagePath(string $path): void
    {
        self::$mockObject->setExportImagePath($path);
    }

    public static function _questionExistsInTest(int $question_id, int $test_id): bool
    {
        return self::$mockObject->_questionExistsInTest($question_id, $test_id);
    }

    public function formatSAQuestion($a_q): string
    {
        return self::$mockObject->formatSAQuestion($a_q);
    }

    protected function getSelfAssessmentFormatter(): \ilAssSelfAssessmentQuestionFormatter
    {
        return self::$mockObject->getSelfAssessmentFormatter();
    }

    public function setPreventRteUsage(bool $prevent_rte_usage): void
    {
        self::$mockObject->setPreventRteUsage($prevent_rte_usage);
    }

    public function getPreventRteUsage(): bool
    {
        return self::$mockObject->getPreventRteUsage();
    }

    public function migrateContentForLearningModule(ilAssSelfAssessmentMigrator $migrator): void
    {
        self::$mockObject->migrateContentForLearningModule($migrator);
    }

    protected function lmMigrateQuestionTypeGenericContent(ilAssSelfAssessmentMigrator $migrator): void
    {
        self::$mockObject->lmMigrateQuestionTypeGenericContent($migrator);
    }

    protected function lmMigrateQuestionTypeSpecificContent(ilAssSelfAssessmentMigrator $migrator): void
    {
        self::$mockObject->lmMigrateQuestionTypeSpecificContent($migrator);
    }

    public function setSelfAssessmentEditingMode(bool $selfassessmenteditingmode): void
    {
        self::$mockObject->setSelfAssessmentEditingMode($selfassessmenteditingmode);
    }

    public function getSelfAssessmentEditingMode(): bool
    {
        return self::$mockObject->getSelfAssessmentEditingMode();
    }

    public function setDefaultNrOfTries(int $defaultnroftries): void
    {
        self::$mockObject->setDefaultNrOfTries($defaultnroftries);
    }

    public function getDefaultNrOfTries(): int
    {
        return self::$mockObject->getDefaultNrOfTries();
    }

    public static function lookupParentObjId(int $question_id): ?int
    {
        return self::$mockObject->lookupParentObjId($question_id);
    }

    protected function duplicateQuestionHints(int $original_question_id, int $duplicate_question_id): void
    {
        self::$mockObject->duplicateQuestionHints(
            $original_question_id,
            $duplicate_question_id
        );
    }

    protected function duplicateSkillAssignments(
        int $srcParentId,
        int $srcQuestionId,
        int $trgParentId,
        int $trgQuestionId
    ): void {
        self::$mockObject->duplicateSkillAssignments(
            $srcParentId,
            $srcQuestionId,
            $trgParentId,
            $trgQuestionId
        );
    }

    public function syncSkillAssignments(
        int $srcParentId,
        int $srcQuestionId,
        int $trgParentId,
        int $trgQuestionId
    ): void {
        self::$mockObject->syncSkillAssignments(
            $srcParentId,
            $srcQuestionId,
            $trgParentId,
            $trgQuestionId
        );
    }

    public function ensureHintPageObjectExists($pageObjectId): void
    {
        self::$mockObject->ensureHintPageObjectExists($pageObjectId);
    }

    public function isAnswered(int $active_id, int $pass): bool
    {
        return self::$mockObject->isAnswered($active_id, $pass);
    }

    public static function isObligationPossible(int $questionId): bool
    {
        return self::$mockObject->isObligationPossible($questionId);
    }

    protected static function getNumExistingSolutionRecords(int $activeId, int $pass, int $questionId): int
    {
        return self::$mockObject->getNumExistingSolutionRecords(
            $activeId,
            $pass,
            $questionId
        );
    }

    public function getAdditionalContentEditingMode(): string
    {
        return self::$mockObject->getAdditionalContentEditingMode();
    }

    public function setAdditionalContentEditingMode(string $additionalContentEditingMode): void
    {
        self::$mockObject->setAdditionalContentEditingMode($additionalContentEditingMode);
    }

    public function isAdditionalContentEditingModePageObject(): bool
    {
        return self::$mockObject->isAdditionalContentEditingModePageObject();
    }

    public function isValidAdditionalContentEditingMode(string $additionalContentEditingMode): bool
    {
        return self::$mockObject->isValidAdditionalContentEditingMode($additionalContentEditingMode);
    }

    public function getValidAdditionalContentEditingModes(): array
    {
        return self::$mockObject->getValidAdditionalContentEditingModes();
    }

    public function getHtmlUserSolutionPurifier(): ilHtmlPurifierInterface
    {
        return self::$mockObject->getHtmlUserSolutionPurifier();
    }

    public function getHtmlQuestionContentPurifier(): ilHtmlPurifierInterface
    {
        return self::$mockObject->getHtmlQuestionContentPurifier();
    }

    protected function buildQuestionDataQuery(): string
    {
        return self::$mockObject->buildQuestionDataQuery();
    }

    public function setLastChange(int $lastChange): void
    {
        self::$mockObject->setLastChange($lastChange);
    }

    public function getLastChange(): ?int
    {
        return self::$mockObject->getLastChange();
    }

    protected function getCurrentSolutionResultSet(int $active_id, int $pass, bool $authorized = true): \ilDBStatement
    {
        return self::$mockObject->getCurrentSolutionResultSet(
            $active_id,
            $pass,
            $authorized
        );
    }

    protected function removeSolutionRecordById(int $solutionId): int
    {
        return self::$mockObject->removeSolutionRecordById($solutionId);
    }

    protected function getSolutionRecordById(int $solutionId): array
    {
        return self::$mockObject->getSolutionRecordById($solutionId);
    }

    public function removeIntermediateSolution(int $active_id, int $pass): void
    {
        self::$mockObject->removeIntermediateSolution($active_id, $pass);
    }

    public function removeCurrentSolution(int $active_id, int $pass, bool $authorized = true): int
    {
        return self::$mockObject->removeCurrentSolution($active_id, $pass, $authorized);
    }

    public function saveCurrentSolution(
        int $active_id,
        int $pass,
        $value1,
        $value2,
        bool $authorized = true,
        $tstamp = 0
    ): int {
        return self::$mockObject->saveCurrentSolution(
            $active_id,
            $pass,
            $value1,
            $value2,
            $authorized,
            $tstamp
        );
    }

    public function updateCurrentSolution(int $solutionId, $value1, $value2, bool $authorized = true): int
    {
        return self::$mockObject->updateCurrentSolution(
            $solutionId,
            $value1,
            $value2,
            $authorized
        );
    }

    public function updateCurrentSolutionsAuthorization(
        int $activeId,
        int $pass,
        bool $authorized,
        bool $keepTime = false
    ): int {
        return self::$mockObject->updateCurrentSolutionsAuthorization(
            $activeId,
            $pass,
            $authorized,
            $keepTime
        );
    }

    public static function implodeKeyValues(array $keyValues): string
    {
        return self::$mockObject->implodeKeyValues($keyValues);
    }

    public static function explodeKeyValues(string $keyValues): array
    {
        return self::$mockObject->explodeKeyValues($keyValues);
    }

    protected function deleteDummySolutionRecord(int $activeId, int $passIndex): void
    {
        self::$mockObject->deleteDummySolutionRecord($activeId, $passIndex);
    }

    protected function isDummySolutionRecord(array $solutionRecord): bool
    {
        return self::$mockObject->isDummySolutionRecord($solutionRecord);
    }

    protected function deleteSolutionRecordByValues(
        int $activeId,
        int $passIndex,
        bool $authorized,
        array $matchValues
    ): void {
        self::$mockObject->deleteSolutionRecordByValues(
            $activeId,
            $passIndex,
            $authorized,
            $matchValues
        );
    }

    protected function duplicateIntermediateSolutionAuthorized(int $activeId, int $passIndex): void
    {
        self::$mockObject->duplicateIntermediateSolutionAuthorized($activeId, $passIndex);
    }

    protected function forceExistingIntermediateSolution(
        int $activeId,
        int $passIndex,
        bool $considerDummyRecordCreation
    ): void {
        self::$mockObject->forceExistingIntermediateSolution(
            $activeId,
            $passIndex,
            $considerDummyRecordCreation
        );
    }

    public function setStep($step): void
    {
        self::$mockObject->setStep($step);
    }

    public function getStep(): ?int
    {
        return self::$mockObject->getStep();
    }

    public static function convertISO8601FormatH_i_s_ExtendedToSeconds(string $time): int
    {
        return self::$mockObject->convertISO8601FormatH_i_s_ExtendedToSeconds($time);
    }

    public function toJSON(): string
    {
        return self::$mockObject->toJSON();
    }

    public function intermediateSolutionExists(int $active_id, int $pass): bool
    {
        return self::$mockObject->intermediateSolutionExists($active_id, $pass);
    }

    public function authorizedSolutionExists(int $active_id, ?int $pass): bool
    {
        return self::$mockObject->authorizedSolutionExists($active_id, $pass);
    }

    public function authorizedOrIntermediateSolutionExists(int $active_id, int $pass): bool
    {
        return self::$mockObject->authorizedOrIntermediateSolutionExists($active_id, $pass);
    }

    protected function lookupMaxStep(int $active_id, int $pass): int
    {
        return self::$mockObject->lookupMaxStep($active_id, $pass);
    }

    public function lookupForExistingSolutions(int $activeId, int $pass): array
    {
        return self::$mockObject->lookupForExistingSolutions($activeId, $pass);
    }

    public function isAddableAnswerOptionValue(int $qIndex, string $answerOptionValue): bool
    {
        return self::$mockObject->isAddableAnswerOptionValue($qIndex, $answerOptionValue);
    }

    public function addAnswerOptionValue(int $qIndex, string $answerOptionValue, float $points): void
    {
        self::$mockObject->addAnswerOptionValue($qIndex, $answerOptionValue, $points);
    }

    public function removeAllExistingSolutions(): void
    {
        self::$mockObject->removeAllExistingSolutions();
    }

    public function removeExistingSolutions(int $activeId, int $pass): int
    {
        return self::$mockObject->removeExistingSolutions($activeId, $pass);
    }

    public function resetUsersAnswer(int $activeId, int $pass): void
    {
        self::$mockObject->resetUsersAnswer($activeId, $pass);
    }

    public function removeResultRecord(int $activeId, int $pass): int
    {
        return self::$mockObject->removeResultRecord($activeId, $pass);
    }

    public function fetchValuePairsFromIndexedValues(array $indexedValues): array
    {
        return self::$mockObject->fetchValuePairsFromIndexedValues($indexedValues);
    }

    public function fetchIndexedValuesFromValuePairs(array $valuePairs): array
    {
        return self::$mockObject->fetchIndexedValuesFromValuePairs($valuePairs);
    }

    public function areObligationsToBeConsidered(): bool
    {
        return self::$mockObject->areObligationsToBeConsidered();
    }

    public function setObligationsToBeConsidered(bool $obligationsToBeConsidered): void
    {
        self::$mockObject->setObligationsToBeConsidered($obligationsToBeConsidered);
    }

    public function updateTimestamp(): void
    {
        self::$mockObject->updateTimestamp();
    }

    public function getTestPresentationConfig(): ilTestQuestionConfig
    {
        return self::$mockObject->getTestPresentationConfig();
    }

    protected function buildTestPresentationConfig(): ilTestQuestionConfig
    {
        return self::$mockObject->buildTestPresentationConfig();
    }

    protected function getSuggestedSolutionsRepo(): SuggestedSolutionsDatabaseRepository
    {
        return self::$mockObject->getSuggestedSolutionsRepo();
    }

    protected function loadSuggestedSolutions(): array
    {
        return self::$mockObject->loadSuggestedSolutions();
    }

    public static function extendedTrim(string $value): string
    {
        return self::$mockObject->extendedTrim($value);
    }

    public function hasWritableOriginalInQuestionPool(): bool
    {
        return self::$mockObject->hasWritableOriginalInQuestionPool();
    }

    public function answerToParticipantInteraction(
        AdditionalInformationGenerator $additional_info,
        int $test_ref_id,
        int $active_id,
        int $pass,
        string $source_ip,
        TestParticipantInteractionTypes $interaction_type
    ): TestParticipantInteraction {
        return self::$mockObject->answerToParticipantInteraction(
            $additional_info,
            $test_ref_id,
            $active_id,
            $pass,
            $source_ip,
            $interaction_type
        );
    }

    public function toQuestionAdministrationInteraction(
        AdditionalInformationGenerator $additional_info,
        int $test_ref_id,
        TestQuestionAdministrationInteractionTypes $interaction_type
    ): TestQuestionAdministrationInteraction {
        return self::$mockObject->toQuestionAdministrationInteraction(
            $additional_info,
            $test_ref_id,
            $interaction_type
        );
    }

    protected function answerToLog(AdditionalInformationGenerator $additional_info, int $active_id, int $pass): array
    {
        return self::$mockObject->answerToLog($additional_info, $active_id, $pass);
    }

    public function getOperators(string $expression): array
    {
        return self::$mockObject->getOperators($expression);
    }

    public function getExpressionTypes(): array
    {
        return self::$mockObject->getExpressionTypes();
    }

    public function getUserQuestionResult(int $active_id, int $pass): ilUserQuestionResult
    {
        return self::$mockObject->getUserQuestionResult($active_id, $pass);
    }

    public function getAvailableAnswerOptions(?int $index = null)
    {
        return self::$mockObject->getAvailableAnswerOptions($index);
    }
}

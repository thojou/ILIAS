<?php

use ILIAS\HTTP\Services;
use ILIAS\Test\Participants\Participant;
use ILIAS\Test\Participants\ParticipantTableActions;
use ILIAS\TestQuestionPool\Skill\SkillAssignmentTableAction;
use ILIAS\UI\Component\Modal\Modal;
use ILIAS\UI\Component\Table\Action\Action;
use ILIAS\UI\Factory as UIFactory;
use ILIAS\UI\URLBuilder;
use ILIAS\UI\URLBuilderToken;
use Psr\Http\Message\ServerRequestInterface;

/**
 * IS a MODAL Action
 */
class SkillAssignmentTableEditAction implements SkillAssignmentTableAction
{
    public const string ACTION_ID = 'edit_assignment';

    public function __construct(
        private readonly ilAssQuestionList $question_list,
        private readonly ilAssQuestionSkillAssignmentList $assignment_list,
        private readonly Services $http,
        private readonly UIFactory $ui_factory,
        private readonly ILIAS\Refinery\Factory $refinery,
        private readonly ilLanguage $lng,
        private readonly ilCtrl $ctrl,
    )
    {
    }

    public function getActionId(): string
    {
        return self::ACTION_ID;
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function getTableAction(
        URLBuilder $url_builder,
        URLBuilderToken $row_id_token,
        URLBuilderToken $action_token,
        URLBuilderToken $action_type_token
    ): Action {
        return $this->ui_factory->table()->action()->single(
            $this->lng->txt('tst_pool_edit_assignment'),
            $url_builder
                ->withParameter($action_token, self::ACTION_ID)
                ->withParameter($action_type_token, SkillAssignmentTableActions::SHOW_ACTION),
            $row_id_token
        )->withAsync();
    }

    public function getModal(
        URLBuilder $url_builder,
        array $selected_assignments,
        bool $all_assignments_selected,
    ): ?Modal {
        return $this->ui_factory->modal()->roundtrip(
            $this->lng->txt('edit'),
            [],
            [],
            $url_builder->buildURI()->__toString()
        );
    }

    public function onSubmit(
        URLBuilder $url_builder,
        ServerRequestInterface $request,
        array $selected_assignments,
        bool $all_assignments_selected,
    ): ?Modal {

    }

    public function allowActionForRecord(ilAssQuestionSkillAssignment $record): bool
    {
        return true;
    }

    public function getSelectionErrorMessage(): string
    {
        return '';
    }
}
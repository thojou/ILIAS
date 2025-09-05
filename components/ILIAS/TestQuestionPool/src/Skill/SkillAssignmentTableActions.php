<?php

use ILIAS\TestQuestionPool\ResponseHandler;
use ILIAS\TestQuestionPool\RequestDataCollector;
use ILIAS\TestQuestionPool\Skill\SkillAssignmentTableAction;
use ILIAS\UI\Component\Modal\Modal;
use ILIAS\UI\Component\Table\Action\Action;
use ILIAS\UI\Component\Table\DataRow;
use ILIAS\UI\Factory as UIFactory;
use ILIAS\UI\Renderer;
use ILIAS\UI\URLBuilder;
use ILIAS\UI\URLBuilderToken;

class SkillAssignmentTableActions
{
    public const string ROW_ID_PARAMETER = 'a_id';
    public const string ACTION_PARAMETER = 'action';
    public const string ACTION_TYPE_PARAMETER = 'action_type';
    public const string SHOW_ACTION = 'showAction';
    public const string SUBMIT_ACTION = 'submitAction';


    /**
     * @param UIFactory  $ui_factory
     * @param ilLanguage $lng
     * @param array<ilAssQuestionSkillAssignment> $assignments
     */
    public function __construct(
        private readonly ilLanguage $lng,
        protected readonly \ilGlobalTemplateInterface $tpl,
        private readonly UIFactory $ui_factory,
        private readonly Renderer $ui_renderer,
        private readonly RequestDataCollector $pool_request,
        private readonly ResponseHandler $pool_response,
        private readonly ilAssQuestionSkillAssignmentList $assignment_list,
        /** @var array<SkillAssignmentTableAction> */
        private readonly array $actions
    ) {
    }

    public function getEnabledActions(
        URLBuilder $url_builder,
        URLBuilderToken $row_id_token,
        URLBuilderToken $action_token,
        URLBuilderToken $action_type_token
    ): array {
        return array_filter(
            array_map(
                function (SkillAssignmentTableAction $action) use (
                    $url_builder,
                    $row_id_token,
                    $action_token,
                    $action_type_token
                ): ?Action {
                    if (!$action->isAvailable()) {
                        return null;
                    }

                    return $action->getTableAction(
                        $url_builder,
                        $row_id_token,
                        $action_token,
                        $action_type_token
                    );
                },
                $this->actions
            )
        );
    }

    public function getAction(string $action_id): ?SkillAssignmentTableAction
    {
        return $this->actions[$action_id] ?? null;
    }

    public function execute(
        URLBuilder $url_builder,
        URLBuilderToken $row_id_token,
        URLBuilderToken $action_token,
        URLBuilderToken $action_type_token
    ): ?Modal {
        return match($this->pool_request->string($action_type_token->getName())) {
            self::SUBMIT_ACTION => $this->submit(
                $url_builder,
                $row_id_token,
                $action_token,
                $action_type_token
            ),
            default => $this->showModal(
                $url_builder,
                $row_id_token,
                $action_token,
                $action_type_token
            ),
        };
    }

    public function onDataRow(DataRow $row, mixed $record): DataRow
    {
        return array_reduce(
            array_keys($this->actions),
            fn(DataRow $c, string $v): DataRow => $this->actions[$v]->allowActionForRecord($record)
                ? $c
                : $c->withDisabledAction($v),
            $row
        );
    }

    protected function showModal(
        URLBuilder $url_builder,
        URLBuilderToken $row_id_token,
        URLBuilderToken $action_token,
        URLBuilderToken $action_type_token
    ): void {
        $action = $this->actions[$this->pool_request->string($action_token->getName())];
        $selected_assignments_from_request = $this->pool_request->getMultiSelectionIds($row_id_token->getName());
        $selected_assignments = $this->resolveSelectedAssignments(
            $action,
            $selected_assignments_from_request
        );

        if ($selected_assignments === []) {
            $error_message = $action->getSelectionErrorMessage() ?? $this->lng->txt('no_valid_participant_selection');
            $this->pool_response->sendAsync(
                $this->ui_renderer->renderAsync(
                    $this->ui_factory->messageBox()->failure(
                        $error_message
                    )
                )
            );
        }

        $this->pool_response->sendAsync(
            $this->ui_renderer->renderAsync(
                $action->getModal(
                    $url_builder
                        ->withParameter($row_id_token, $selected_assignments_from_request)
                        ->withParameter($action_token, $action->getActionId())
                        ->withParameter($action_type_token, self::SUBMIT_ACTION),
                    $selected_assignments,
                    $selected_assignments_from_request === 'ALL_OBJECTS'
                )
            )
        );
    }

    protected function submit(
        URLBuilder $url_builder,
        URLBuilderToken $row_id_token,
        URLBuilderToken $action_token,
        URLBuilderToken $action_type_token
    ): ?Modal
    {
        $action = $this->actions[$this->pool_request->string($action_token->getName())];
        $selected_assignments_from_request = $this->pool_request->getMultiSelectionIds($row_id_token->getName());
        $selected_assignments = $this->resolveSelectedAssignments(
            $action,
            $selected_assignments_from_request
        );

        if ($selected_assignments === []) {
            $error_message = $action->getSelectionErrorMessage() ?? $this->lng->txt('no_valid_participant_selection');
            $this->tpl->setOnScreenMessage(
                \ilGlobalTemplateInterface::MESSAGE_TYPE_FAILURE,
                $error_message,
                true
            );
        }

        return $action->onSubmit(
            $url_builder
                ->withParameter($row_id_token, $selected_assignments_from_request)
                ->withParameter($action_token, $action->getActionId())
                ->withParameter($action_type_token, self::SUBMIT_ACTION),
            $this->pool_request->getRequest(),
            $selected_assignments,
            $selected_assignments_from_request === 'ALL_OBJECTS'
        );
    }

    /**
     * @param array<int>|string $selected_assignments
     *
     * @return array<ilAssQuestionSkillAssignment>
     */
    protected function resolveSelectedAssignments(SkillAssignmentTableAction $action, array|string $selected_assignments): array
    {
        if ($selected_assignments === 'ALL_OBJECTS') {
            return array_filter(
                $this->assignment_list->getAssignmentsByQuestionId($this->pool_request->getQuestionId()),
                fn(ilAssQuestionSkillAssignment $assignment) => $action->allowActionForRecord($assignment)
            );
        }

        return array_filter(
            array_map(
                fn(int $user_id) => current($this->assignment_list->getAssignmentsByQuestionId($this->pool_request->getQuestionId())),
                $selected_assignments
            ),
            fn(ilAssQuestionSkillAssignment $assignment) => $action->allowActionForRecord($assignment)
        );
    }
}
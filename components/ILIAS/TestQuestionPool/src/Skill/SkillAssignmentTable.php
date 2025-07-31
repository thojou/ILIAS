<?php

use ILIAS\Data\Order;
use ILIAS\Data\Range;
use ILIAS\HTTP\Services;
use ILIAS\UI\Component\Table\DataRetrieval;
use ILIAS\UI\Component\Table\DataRowBuilder;
use ILIAS\UI\Factory as UIFactory;
use ILIAS\UI\URLBuilder;
use ILIAS\UI\URLBuilderToken;

class SkillAssignmentTable implements DataRetrieval
{
    private const string ID = 'ska';
    private const string ROW_ID_PARAMETER = 'a_id';
    private const string ACTION_PARAMETER = 'action';
    private const string ACTION_TYPE_PARAMETER = 'action_type';
    private const string SHOW_ACTION = 'showAction';
    private const string SUBMIT_ACTION = 'submitAction';


    /**
     * @param UIFactory  $ui_factory
     * @param ilLanguage $lng
     * @param array<ilAssQuestionSkillAssignment> $assignments
     */
    public function __construct(
        private readonly ilAssQuestionList $question_list,
        private readonly ilAssQuestionSkillAssignmentList $assignment_list,
        private readonly Services $http,
        private readonly UIFactory $ui_factory,
        private readonly ILIAS\Refinery\Factory $refinery,
        private readonly ilLanguage $lng,
        private readonly ilCtrl $ctrl,
        private readonly array $assignments
    )
    {
    }

    public function execute(URLBuilder $url_builder): array
    {
        [$url_builder, $row_id_token, $action_token] = $this->acquireParameters($url_builder);

        $action_id = $this->http->wrapper()->query()->retrieve(
            $action_token->getName(),
            $this->refinery->byTrying([
                $this->refinery->kindlyTo()->string(),
                $this->refinery->always(null)
            ])
        );

        return match ($action_id !== null) {
            true => $this->handleAction($action_id, $url_builder, $row_id_token, $action_token),
            default => $this->getComponents($url_builder, $row_id_token, $action_token)
        };
    }

    private function handleAction(string $action_id, $url_builder, $row_id_token, $action_token)
    {
        $action = new SkillAssignmentEditAction(
            $this->question_list,
            $this->assignment_list,
            $this->http,
            $this->ui_factory,
            $this->refinery,
            $this->lng,
            $this->ctrl,
        );
        return $action->execute(
            $url_builder->withParameter($action_token, $action_id),
            $row_id_token
        );

    }

    public function getComponents(
        URLBuilder $url_builder,
        URLBuilderToken $row_id_token,
        URLBuilderToken $action_token,
        URLBuilderToken $action_type_token
    ): array
    {
        return [
            $this->ui_factory->table()->data($this, 'Skill Question Assignment', [
                "competence" => $this->ui_factory->table()->column()->text(
                    $this->lng->txt('tst_competence')
                )->withIsSortable(true),
                "eval_mode" => $this->ui_factory->table()->column()->text(
                    $this->lng->txt('tst_comp_eval_mode')
                )->withIsSortable(true),
                "points" => $this->ui_factory->table()->column()->number(
                    $this->lng->txt('tst_comp_points')
                )->withIsSortable(true)
            ])
                ->withActions([
                    $this->ui_factory->table()->action()->single(
                        $this->lng->txt('tst_edit_competence_assign'),
                        $url_builder
                            ->withParameter($action_token, 'edit')
                            ->withParameter($action_type_token, self::SHOW_ACTION),
                        $row_id_token
                    )
                ])
                ->withRequest($this->http->request())
        ];
    }

    public function getRows(
        DataRowBuilder $row_builder,
        array $visible_column_ids,
        Range $range,
        Order $order,
        ?array $filter_data,
        ?array $additional_parameters
    ): Generator {
        foreach($this->assignments as $item) {
            yield $row_builder->buildDataRow(
                "{$item->getQuestionId()}_{$item->getSkillBaseId()}",
                [
                    'competence' => htmlspecialchars($item->getSkillTitle(), ENT_QUOTES, 'UTF-8', false),
                    'eval_mode' => $this->lng->txt($item->hasEvalModeBySolution()
                        ? 'qpl_skill_point_eval_mode_solution_compare'
                        : 'qpl_skill_point_eval_mode_quest_result'),
                    'points' => $item->getSkillPoints(),
                ]
            );
        }
    }

    public function getTotalRowCount(?array $filter_data, ?array $additional_parameters): ?int
    {
        return count($this->assignments);
    }

    public function acquireParameters($url_builder): array
    {
        return $url_builder->acquireParameters(
            [self::ID],
            self::ROW_ID_PARAMETER,
            self::ACTION_PARAMETER,
            self::ACTION_TYPE_PARAMETER
        );
    }
}
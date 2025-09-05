<?php

use ILIAS\Badge\Modal;
use ILIAS\Data\Order;
use ILIAS\Data\Range;
use ILIAS\HTTP\Services;
use ILIAS\TestQuestionPool\RequestDataCollector;
use ILIAS\UI\Component\Component;
use ILIAS\UI\Component\Table\DataRetrieval;
use ILIAS\UI\Component\Table\DataRowBuilder;
use ILIAS\UI\Factory as UIFactory;
use ILIAS\UI\URLBuilder;
use ILIAS\UI\URLBuilderToken;

class SkillAssignmentTable implements DataRetrieval
{
    private const string ID = 'ska';
    private ?iterable $records = null;

    public function __construct(
        private readonly RequestDataCollector $pool_request,
        private readonly ilAssQuestionSkillAssignmentList $assignment_list,
        private readonly UIFactory $ui_factory,
        private readonly ilLanguage $lng,
        private readonly SkillAssignmentTableActions $table_actions
    ) {
    }

    public function execute(URLBuilder $url_builder): ?Modal
    {
        $this->table_actions->execute(...$this->acquireParameters($url_builder));
    }

    /**
     * @param URLBuilder      $url_builder
     * @param URLBuilderToken $row_id_token
     * @param URLBuilderToken $action_token
     * @param URLBuilderToken $action_type_token
     *
     * @return array<Component>
     */
    public function getComponents(
        URLBuilder $url_builder,
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
                ->withActions(
                    $this->table_actions->getEnabledActions(
                        ...$this->acquireParameters($url_builder)
                    )
                )
                ->withRequest($this->pool_request->getRequest())
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
        foreach($this->loadRecords() as $record) {

            yield $this->table_actions->onDataRow(
                $row_builder->buildDataRow(
                    "{$record->getQuestionId()}_{$record->getSkillBaseId()}",
                    [
                        'competence' => htmlspecialchars($record->getSkillTitle(), ENT_QUOTES, 'UTF-8', false),
                        'eval_mode' => $this->lng->txt($record->hasEvalModeBySolution()
                            ? 'qpl_skill_point_eval_mode_solution_compare'
                            : 'qpl_skill_point_eval_mode_quest_result'),
                        'points' => $record->getSkillPoints(),
                    ]
                ),
                $record
            );
        }
    }

    public function getTotalRowCount(?array $filter_data, ?array $additional_parameters): ?int
    {
        return count($this->assignment_list->getAssignmentsByQuestionId($this->pool_request->getQuestionId()));
    }

    public function acquireParameters($url_builder): array
    {
        return $url_builder->acquireParameters(
            [self::ID],
            SkillAssignmentTableActions::ROW_ID_PARAMETER,
            SkillAssignmentTableActions::ACTION_PARAMETER,
            SkillAssignmentTableActions::ACTION_TYPE_PARAMETER
        );
    }

    private function loadRecords(): iterable
    {
        if ($this->records !== null) {
            return $this->records;
        }

        $this->records = iterator_to_array(
            $this->assignment_list->getAssignmentsByQuestionId($this->pool_request->getQuestionId())
        );

        return $this->records;
    }
}
<?php

use ILIAS\HTTP\Services;
use ILIAS\Test\Participants\ParticipantTableActions;
use ILIAS\UI\Component\Table\Presentation;
use ILIAS\UI\Component\Table\PresentationRow;
use ILIAS\UI\Factory as UIFactory;
use ILIAS\UI\URLBuilder;
use ILIAS\UI\URLBuilderToken;

class SkillTable
{
    private const string ID = 'sk';
    private const string ROW_ID_PARAMETER = 'q_id';
    private const string ACTION_PARAMETER = 'action';

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

    public function execute(URLBuilder $url_builder, array $data): array
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
            default => $this->getComponents($url_builder, $row_id_token, $action_token, $data)
        };
    }

    private function handleAction(string $action_id, $url_builder, $row_id_token, $action_token)
    {
        $action = new SkillAssignmentManageAction(
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
        $data
    ): array
    {
        return [
            $this->ui_factory->table()->presentation(
                'Fragen-Kompetenz-Zuordnung',
                [],
                fn (PresentationRow $row, array $record) => $this->mapRow(
                    $row,
                    $record,
                    $url_builder,
                    $row_id_token,
                    $action_token
                )
            )->withData($data)
        ];
    }

    private function mapRow(
        PresentationRow $row,
        array $record,
        URLBuilder $url_builder,
        URLBuilderToken $row_id_token,
        URLBuilderToken $action_token
    ): PresentationRow
    {
        $question = $record['question'];
        $assignments = $record['assignments'];

        $assignment_details = [];
        foreach($assignments as $skill_assignment) {
            $assignment_details[$skill_assignment->getSkillTitle()] = $this->ui_factory
                ->listing()
                ->property()
                ->withProperty(
                    $this->lng->txt('tree'),
                    $skill_assignment->getSkillPath()
                )
                ->withProperty(
                    $this->lng->txt('tst_comp_eval_mode'),
                    $this->lng->txt(
                        $skill_assignment->hasEvalModeBySolution()
                            ? 'qpl_skill_point_eval_mode_solution_compare'
                            : 'qpl_skill_point_eval_mode_quest_result'
                    )
                )
                ->withProperty(
                    $this->lng->txt('tst_comp_points'),
                    $skill_assignment->getSkillPoints()
                );
        }

        $row = $row
            ->withHeadline($question['title'])
            ->withSubheadline($question['description'])
            ->withLeadingSymbol(
                $this->ui_factory->symbol()->icon()->standard('ques', "")
            )
            ->withContent(
                !empty($assignments)
                    ? $this->ui_factory->listing()->descriptive($assignment_details)
                    : $this->ui_factory->legacy()->content($this->lng->txt('ui_table_no_records'))
            )
            ->withAction(
                $this->ui_factory->button()->standard(
                    $this->lng->txt('tst_manage_competence_assigns'),
                    (string) $url_builder
                        ->withParameter($row_id_token, $question['question_id'])
                        ->withParameter($action_token, 'manage')
                        ->buildURI()
                )
            );

        if (!empty($assignments)) {
            $row = $row->withImportantFields([
                $this->lng->txt('tst_competence') => join(
                    ", ",
                    array_map(
                        fn (ilAssQuestionSkillAssignment $a) => $a->getSkillTitle(),
                        $assignments
                    )
                )
            ]);
        }

        return $row;
    }

    public function acquireParameters($url_builder): array
    {
        return $url_builder->acquireParameters(
            [self::ID],
            self::ROW_ID_PARAMETER,
            self::ACTION_PARAMETER
        );
    }

    private function orderQuestionData($questionData)
    {
        $orderedQuestionsData = [];

        if ($this->getQuestionOrderSequence()) {
            foreach ($this->getQuestionOrderSequence() as $questionId) {
                $orderedQuestionsData[$questionId] = $questionData[$questionId];
            }

            return $orderedQuestionsData;
        }

        foreach ($questionData as $questionId => $data) {
            $orderedQuestionsData[$questionId] = $data['title'];
        }

        $orderedQuestionsData = $this->sortAlphabetically($orderedQuestionsData);

        foreach ($orderedQuestionsData as $questionId => $questionTitle) {
            $orderedQuestionsData[$questionId] = $questionData[$questionId];
        }

        return $orderedQuestionsData;
    }
}
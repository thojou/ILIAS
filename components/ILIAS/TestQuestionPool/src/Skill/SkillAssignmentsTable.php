<?php

use ILIAS\Data\URI;
use ILIAS\UI\Component\Table\PresentationRow;
use ILIAS\UI\Factory as UIFactory;

class SkillAssignmentsTable
{
    private const string ROW_ID_PARAMETER = 'q_id';
    /** @var array<SkillAssignments>|null */
    private ?array $records = null;

    public function __construct(
        private readonly ilAssQuestionList $question_list,
        private readonly ilAssQuestionSkillAssignmentList $assignment_list,
        private readonly UIFactory $ui_factory,
        private readonly ilLanguage $lng,
    )
    {
    }

    public function getComponents(URI $edit_uri): array
    {
        return [
            $this->ui_factory->table()->presentation(
                'Fragen-Kompetenz-Zuordnung',
                [],
                fn (PresentationRow $row, SkillAssignments $record) => $this->mapRow($row, $record, $edit_uri)
            )->withData($this->loadRecords())
        ];
    }

    /**
     * @param PresentationRow  $row
     * @param SkillAssignments $record
     * @param URI              $edit_uri
     *
     * @return PresentationRow
     */
    private function mapRow(
        PresentationRow $row,
        SkillAssignments $record,
        URI $edit_uri
    ): PresentationRow
    {
        $assignment_details = [];
        foreach($record->getSkillAssignments() as $skill_assignment) {
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
            ->withHeadline($record->getQuestion()['title'])
            ->withSubheadline($record->getQuestion()['description'])
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
                    (string) $edit_uri
                        ->withParameter(self::ROW_ID_PARAMETER, $record->getQuestion()['question_id'])
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

    /**
     * @return array<SkillAssignments>
     */
    public function loadRecords(): array
    {
        if ($this->records !== null) {
            return $this->records;
        }

        //$questions = $this->orderQuestionData($this->question_list->getQuestionDataArray());
        $questions = $this->question_list->getQuestionDataArray();
        $records = [];
        foreach($questions as $question_id => $question_data) {
            $assignments = $this->assignment_list->getAssignmentsByQuestionId($question_id);

            $records[] = new SkillAssignments($question_data, $assignments);
        }

        $this->records = $records;

        return $this->records;
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
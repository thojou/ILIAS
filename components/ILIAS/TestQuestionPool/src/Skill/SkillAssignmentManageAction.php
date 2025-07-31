<?php

use ILIAS\HTTP\Services;
use ILIAS\UI\Factory as UIFactory;
use ILIAS\UI\URLBuilder;

class SkillAssignmentManageAction
{

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

    public function execute(URLBuilder $url_builder, $row_id_token): array
    {
        $q_id = $this->http->wrapper()->query()->retrieve(
            $row_id_token->getName(),
            $this->refinery->byTrying([
                $this->refinery->kindlyTo()->int(),
                $this->refinery->always(null)
            ])
        );

        return (new SkillAssignmentTable(
            $this->question_list,
            $this->assignment_list,
            $this->http,
            $this->ui_factory,
            $this->refinery,
            $this->lng,
            $this->ctrl,
            $this->assignment_list->getAssignmentsByQuestionId($q_id)
        ))->execute($url_builder->withParameter($row_id_token, $q_id));
    }

}
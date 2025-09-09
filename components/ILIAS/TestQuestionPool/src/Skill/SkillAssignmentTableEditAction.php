<?php

/**
 * This file is part of ILIAS, a powerful learning management system
 * published by ILIAS open source e-Learning e.V.
 *
 * ILIAS is licensed with the GPL-3.0,
 * see https://www.gnu.org/licenses/gpl-3.0.en.html
 * You should have received a copy of said license along with the
 * source code, too.
 *
 * If this is not the case or you just want to try ILIAS, you'll find
 * us at:
 * https://www.ilias.de
 * https://github.com/ILIAS-eLearning
 *
 *********************************************************************/

declare(strict_types=1);

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

    public function __construct(private readonly UIFactory $ui_factory, private readonly ilLanguage $lng) {}

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
        // TODO: implement
        return null;
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

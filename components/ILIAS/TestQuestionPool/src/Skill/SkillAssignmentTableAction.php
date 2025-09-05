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

namespace ILIAS\TestQuestionPool\Skill;

use ilAssQuestionSkillAssignment;
use ILIAS\UI\URLBuilder;
use ILIAS\UI\URLBuilderToken;
use ILIAS\UI\Component\Modal\Modal;
use ILIAS\UI\Component\Table\Action\Action;
use Psr\Http\Message\ServerRequestInterface;

interface SkillAssignmentTableAction
{
    public function getActionId(): string;
    public function isAvailable(): bool;
    public function getTableAction(
        URLBuilder $url_builder,
        URLBuilderToken $row_id_token,
        URLBuilderToken $action_token,
        URLBuilderToken $action_type_token
    ): Action;

    /**
     * @param array<ilAssQuestionSkillAssignment> $selected_assignments
     */
    public function getModal(
        URLBuilder $url_builder,
        array $selected_assignments,
        bool $all_assignments_selected
    ): ?Modal;

    /**
     * @param array<ilAssQuestionSkillAssignment> $selected_assignments
     */
    public function onSubmit(
        URLBuilder $url_builder,
        ServerRequestInterface $request,
        array $selected_assignments,
        bool $all_assignments_selected
    ): ?Modal;
    public function allowActionForRecord(ilAssQuestionSkillAssignment $record): bool;
    public function getSelectionErrorMessage(): ?string;
}

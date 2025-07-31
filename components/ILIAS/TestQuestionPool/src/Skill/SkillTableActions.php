<?php

use ILIAS\UI\Component\Modal\Modal;
use ILIAS\UI\Component\Table\DataRow;
use ILIAS\UI\URLBuilder;
use ILIAS\UI\URLBuilderToken;

class SkillTableActions
{
    public function getEnabledActions(
        URLBuilder $url_builder,
        URLBuilderToken $row_id_token,
        URLBuilderToken $action_token,
        URLBuilderToken $action_type_token
    ): array {

    }

    public function getAction(string $action_id): ?ActionInterface
    {
        return $this->actions[$action_id] ?? null;
    }

    public function execute(
        URLBuilder $url_builder,
        URLBuilderToken $row_id_token,
        URLBuilderToken $action_token,
        URLBuilderToken $action_type_token
    ): ?Modal {

    }

    public function onDataRow(DataRow $row, mixed $record): DataRow
    {

    }

    protected function show()
    {

    }

    protected function submit()
    {

    }
}
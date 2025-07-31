<?php

use ILIAS\UI\Component\Table\Action\Action;

interface ActionInterface
{
    public function getActionId(): string;
    public function isAvailable(): bool;
    public function getAction(): Action;
    public function allowActionForRecord(mixed $record): bool;
}
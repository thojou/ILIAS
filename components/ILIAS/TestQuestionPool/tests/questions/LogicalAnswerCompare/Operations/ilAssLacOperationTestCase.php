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

use PHPUnit\Framework\TestCase;

abstract class ilAssLacOperationTestCase extends TestCase
{
    public function test_operation(): void
    {
        $operation_class = $this->getOperationClass();
        $operation = new $operation_class();

        $this->assertInstanceOf(ilAssLacAbstractOperation::class, $operation);
        $this->assertEquals($this->getExpectedPattern(), $operation->getPattern());
        $this->assertEquals($this->getExpectedPattern(), $this->getOperationClass()::$pattern);
        $this->assertEquals($this->getExpectedDescription(), $operation->getDescription());
        $this->assertFalse($operation->isNegated());

        $operation->setNegated(true);

        $this->assertTrue($operation->isNegated());
    }

    abstract protected function getOperationClass(): string;

    abstract protected function getExpectedPattern(): string;

    abstract protected function getExpectedDescription(): string;
}

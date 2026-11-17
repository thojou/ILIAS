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
 */

declare(strict_types=1);

use ILIAS\DI\Container;
use PHPUnit\Framework\TestCase;

class ilECSImportedObjectIdTest extends TestCase
{
    public function testTypeLookupAcceptsImportedObjectIdFromDatabaseRow(): void
    {
        $row = new stdClass();
        $row->econtent_id = '7';
        $row->obj_id = '42';

        $statement = $this->createMock(ilDBStatement::class);
        $statement->method('fetchRow')
            ->with(ilDBConstants::FETCHMODE_OBJECT)
            ->willReturnOnConsecutiveCalls($row, null);

        $db = $this->getMockBuilder(ilDBPdoMySQLInnoDB::class)
            ->disableOriginalConstructor()
            ->getMock();
        $db->method('quote')->willReturn('1');
        $db->method('in')->willReturn("type IN ('rcrs')");
        $db->method('query')->willReturn($statement);

        $cache = $this->createMock(ilObjectDataCache::class);
        $cache->expects($this->once())
            ->method('lookupType')
            ->with(42)
            ->willReturn('rcrs');

        $dic = new Container();
        $GLOBALS['DIC'] = $dic;
        $dic['ilObjDataCache'] = static fn() => $cache;
        $dic['lng'] = static fn() => new stdClass();

        $manager = (new ReflectionClass(ilECSImportManager::class))->newInstanceWithoutConstructor();
        (new ReflectionProperty(ilECSImportManager::class, 'db'))->setValue($manager, $db);

        $imported = $manager->getAllImportedRemoteObjects(1);

        $this->assertSame('rcrs', ilObject::_lookupType($imported[7]));
    }
}

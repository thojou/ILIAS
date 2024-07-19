<?php

use PHPUnit\Framework\MockObject\MockObject;

/**
 * @template T
 */
trait TestMockAdapterTrait
{
    /**
     * @var ?MockObject&T
     */
    private static ?MockObject $mockObject = null;

    /**
     * @param MockObject&T $mockObject
     *
     * @return void
     */
    public static function setMockObject(MockObject $mockObject): void
    {
        self::$mockObject = $mockObject;
    }

    public static function tearDown(): void
    {
        self::$mockObject = null;
    }
}

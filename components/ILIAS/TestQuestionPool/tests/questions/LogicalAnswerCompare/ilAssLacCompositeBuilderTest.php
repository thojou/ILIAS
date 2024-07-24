<?php

namespace questions\LogicalAnswerCompare;

use ilAssLacAbstractComposite;
use ilAssLacCompositeBuilder;
use ilAssLacCompositeBuilderException;
use ilAssLacConditionParser;
use ilAssLacConditionParserException;
use ilAssLacMissingBracket;
use PHPUnit\Framework\TestCase;

class ilAssLacCompositeBuilderTest extends TestCase
{
    private ilAssLacCompositeBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new ilAssLacCompositeBuilder();
    }

    public function test_instance(): void
    {
        $this->assertInstanceOf(ilAssLacCompositeBuilder::class, $this->builder);
    }

    public function test_create_invalid(): void
    {
        $this->expectException(ilAssLacCompositeBuilderException::class);

        $this->builder->create(['type' => 'unknown']);
    }
}

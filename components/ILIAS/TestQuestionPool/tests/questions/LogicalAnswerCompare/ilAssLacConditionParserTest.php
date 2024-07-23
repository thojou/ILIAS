<?php

use PHPUnit\Framework\TestCase;

class ilAssLacConditionParserTest extends TestCase
{
    private ilAssLacConditionParser $parser;

    protected function setUp(): void
    {
        $this->parser = new ilAssLacConditionParser();
    }

    public function test_instance(): void
    {
        $this->assertInstanceOf(ilAssLacConditionParser::class, $this->parser);

    }

    /**
     * @dataProvider provideParseData
     */
    public function test_parse(string $condition): void
    {
        #$this->assertEmpty($this->parser->getExpressions()); // todo: should not be allowed to initialized

        $composite = $this->parser->parse($condition);

        $this->assertInstanceOf(ilAssLacAbstractComposite::class, $composite);
        $this->assertEmpty($this->parser->getExpressions());
        $this->assertEquals(
            '((Frage 5 mit genau Anwort 3 beantwortet ) und (Aktuelle Fragemit genau Hello beantwortet ) ) ',
            $composite->describe()
        );
    }

    public static function provideParseData(): array
    {
        return [
            //['(Q5 = +3+ & !R = ~Hello~) | !(Q3[2])']
            ['Q5 = +3+ & !R = ~Hello~']
        ];
    }

    /**
     * @dataProvider provideParseInvalidData
     */
    public function test_parseInvalid(string $condition, string $expectedException): void
    {
        $this->expectException($expectedException);
        $this->parser->parse($condition);
    }

    public static function provideParseInvalidData(): array
    {
        return [
            ['(', ilAssLacMissingBracket::class],
            [')', ilAssLacMissingBracket::class],
            ['(Q5 =)', ilAssLacConditionParserException::class]
        ];
    }
}

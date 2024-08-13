<?php

namespace Nathanmac\Utilities\Parser\Tests;

use \Mockery as m;
use Nathanmac\Utilities\Parser\Parser;
use PHPUnit\Framework\TestCase;

class QueryStrTest extends TestCase
{
    /**
     * Tear down after tests
     */
    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function test_parse_auto_detect_query_string_data()
    {
        $parser = m::mock('Nathanmac\Utilities\Parser\Parser')
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $parser->shouldReceive('getFormatClass')
            ->once()
            ->andReturn('Nathanmac\Utilities\Parser\Formats\QueryStr');

        $parser->shouldReceive('getPayload')
            ->once()
            ->andReturn('status=123&message=hello world');

        $this->assertEquals(['status' => 123, 'message' => 'hello world'], $parser->payload());
    }

    public function test_parser_validates_query_string_data()
    {
        $parser = new Parser();
        $this->assertEquals(['status' => 123, 'message' => 'hello world'], $parser->querystr('status=123&message=hello world'));
    }

    public function test_parser_empty_query_string_data()
    {
        $parser = new Parser();
        $this->assertEquals([], $parser->querystr(""));
    }

    public function test_format_detection_query_string()
    {
        $parser = new Parser();

        $_SERVER['HTTP_CONTENT_TYPE'] = "application/x-www-form-urlencoded";
        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\QueryStr', $parser->getFormatClass());

        unset($_SERVER['HTTP_CONTENT_TYPE']);
    }
}

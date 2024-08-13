<?php

namespace Nathanmac\Utilities\Parser\Tests;

use \Mockery as m;
use Nathanmac\Utilities\Parser\Parser;
use PHPUnit\Framework\TestCase;

class JSONTest extends TestCase
{
    /**
     * Tear down after tests
     */
    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function test_parse_auto_detect_json_data()
    {
        $parser = m::mock('Nathanmac\Utilities\Parser\Parser')
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $parser->shouldReceive('getFormatClass')
            ->twice()
            ->andReturn('Nathanmac\Utilities\Parser\Formats\JSON');

        $parser->shouldReceive('getPayload')
            ->once()
            ->andReturn('{"status":123, "message":"hello world"}');

        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\JSON', $parser->getFormatClass());
        $this->assertEquals(['status' => 123, 'message' => 'hello world'], $parser->payload());
    }

    public function test_parser_validates_json_data()
    {
        $parser = new Parser();
        $this->assertEquals(['status' => 123, 'message' => 'hello world'], $parser->json('{"status":123, "message":"hello world"}'));
    }

    public function test_parser_empty_json_data()
    {
        $parser = new Parser();
        $this->assertEquals([], $parser->json(""));
    }

    public function test_throws_an_exception_when_parsed_json_bad_data()
    {
        $parser = new Parser();
        $this->expectException('Exception');
        $this->expectExceptionMessage('Failed To Parse JSON');
        $parser->json('as|df>ASFBw924hg2=');
    }

    public function test_format_detection_json()
    {
        $parser = new Parser();

        $_SERVER['HTTP_CONTENT_TYPE'] = "application/json";
        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\JSON', $parser->getFormatClass());

        $_SERVER['HTTP_CONTENT_TYPE'] = "application/x-javascript";
        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\JSON', $parser->getFormatClass());

        $_SERVER['HTTP_CONTENT_TYPE'] = "text/javascript";
        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\JSON', $parser->getFormatClass());

        $_SERVER['HTTP_CONTENT_TYPE'] = "text/x-javascript";
        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\JSON', $parser->getFormatClass());

        $_SERVER['HTTP_CONTENT_TYPE'] = "text/x-json";
        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\JSON', $parser->getFormatClass());

        unset($_SERVER['HTTP_CONTENT_TYPE']);
    }
}

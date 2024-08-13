<?php

namespace Nathanmac\Utilities\Parser\Tests;

use \Mockery as m;
use Nathanmac\Utilities\Parser\Parser;
use PHPUnit\Framework\TestCase;

class SerializeTest extends TestCase
{
    /**
     * Tear down after tests
     */
    protected function tearDown(): void
    {
        m::close();
        parent::tearDown();
    }

    public function test_parse_auto_detect_serialized_data()
    {
        $parser = m::mock('Nathanmac\Utilities\Parser\Parser')
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $parser->shouldReceive('getFormatClass')
            ->once()
            ->andReturn('Nathanmac\Utilities\Parser\Formats\Serialize');

        $parser->shouldReceive('getPayload')
            ->once()
            ->andReturn('a:2:{s:6:"status";i:123;s:7:"message";s:11:"hello world";}');

        $this->assertEquals(['status' => 123, 'message' => 'hello world'], $parser->payload());
    }

    public function test_parser_validates_serialized_data()
    {
        $parser = new Parser();
        $this->assertEquals(['status' => 123, 'message' => 'hello world'], $parser->serialize('a:2:{s:6:"status";i:123;s:7:"message";s:11:"hello world";}'));
    }

    public function test_parser_empty_serialized_data()
    {
        $parser = new Parser();
        $this->assertEquals([], $parser->serialize(""));
    }

    public function test_throws_an_exception_when_parsed_serialized_bad_data()
    {
        $parser = new Parser();
        $this->expectException('Exception');
        $this->expectExceptionMessage('Failed To Parse Serialized Data');
        $parser->serialize('as|df>ASFBw924hg2=');
    }

    public function test_format_detection_serialized()
    {
        $parser = new Parser();

        $_SERVER['HTTP_CONTENT_TYPE'] = "application/vnd.php.serialized";
        $this->assertEquals('Nathanmac\Utilities\Parser\Formats\Serialize', $parser->getFormatClass());

        unset($_SERVER['HTTP_CONTENT_TYPE']);
    }
}

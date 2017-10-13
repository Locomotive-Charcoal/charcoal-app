<?php

namespace Charcoal\Tests\App\Config;

use Exception;
use TypeError;
use InvalidArgumentException;

// From 'charcoal-app'
use Charcoal\App\Config\LoggerConfig;

/**
 *
 */
class LoggerConfigTest extends \PHPUnit_Framework_TestCase
{
    public $obj;

    public function setUp()
    {
        $this->obj = new LoggerConfig();
    }

    public function testDefaults()
    {
        $this->assertEquals('charcoal', LoggerConfig::DEFAULT_CHANNEL);

        $this->assertTrue($this->obj->active());

        $handlers = $this->obj->handlers();
        $this->assertCount(2, $handlers);
        $this->assertArrayHasKey('stream', $handlers);
        $this->assertArraySubset([ 'stream' => [ 'type' => 'stream' ] ], $handlers);
        $this->assertArrayHasKey('console', $handlers);
        $this->assertArraySubset([ 'console' => [ 'type' => 'browser-console' ] ], $handlers);

        $processors = $this->obj->processors();
        $this->assertCount(2, $processors);
        $this->assertContains([ 'type' => 'uid' ], $processors);
        $this->assertContains([ 'type' => 'memory-usage' ], $processors);

        $this->assertEquals(LoggerConfig::DEFAULT_CHANNEL, $this->obj->channel());
    }

    public function testSetActive()
    {
        $ret = $this->obj->setActive(false);
        $this->assertSame($ret, $this->obj);
        $this->assertFalse($this->obj->active());
    }

    public function testSetHandlers()
    {
        $ret = $this->obj->setHandlers([ 'errlog' => [ 'type' => 'error-log' ], [ 'type' => 'mail' ] ]);
        $this->assertSame($ret, $this->obj);

        $handlers = $this->obj->handlers();
        $this->assertCount(2, $handlers);
        $this->assertArrayHasKey('errlog', $handlers);
        $this->assertArraySubset([ 'errlog' => [ 'type' => 'error-log' ] ], $handlers);
        $this->assertContains([ 'type' => 'mail' ], $handlers);

        $this->setExpectedException(InvalidArgumentException::class);
        $this->obj->setHandlers([ [ 'foo' => 'baz' ] ]);
    }

    public function testInvalidHandlers()
    {
        if (PHP_MAJOR_VERSION > 5) {
            $this->setExpectedException(TypeError::class);
        } else {
            $this->setExpectedException(\PHPUnit_Framework_Error::class);
        }
        $this->obj->addHandlers([ false ]);
    }

    public function testSetProcessors()
    {
        $ret = $this->obj->setProcessors([ 'web' => [ 'type' => 'web' ], [ 'type' => 'process-id' ] ]);
        $this->assertSame($ret, $this->obj);

        $processors = $this->obj->processors();
        $this->assertCount(2, $processors);
        $this->assertArrayHasKey('web', $processors);
        $this->assertArraySubset([ 'web' => [ 'type' => 'web' ] ], $processors);
        $this->assertContains([ 'type' => 'process-id' ], $processors);

        $this->setExpectedException(InvalidArgumentException::class);
        $this->obj->setProcessors([ [ 'foo' => 'baz' ] ]);
    }

    public function testInvalidProcessors()
    {
        if (PHP_MAJOR_VERSION > 5) {
            $this->setExpectedException(TypeError::class);
        } else {
            $this->setExpectedException(\PHPUnit_Framework_Error::class);
        }
        $this->obj->addProcessors([ false ]);
    }

    public function testSetChannel()
    {
        $ret = $this->obj->setChannel('foo');
        $this->assertSame($ret, $this->obj);
        $this->assertEquals('foo', $this->obj->channel());

        $this->setExpectedException(InvalidArgumentException::class);
        $this->obj->setChannel(false);
    }
}
